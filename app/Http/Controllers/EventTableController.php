<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventTable;
use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class EventTableController extends Controller
{
    /**
     * Function to upload a CSV file into the EventTable
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadCSVfile(Request $request)
    {
        try {
            // Get the uploaded file
            $file = $request->file('upload-file');

            if (!$file) {
                return redirect()->back()->with('error', 'No file uploaded.');
            }

            $file_path = $file->getRealPath();

            // Open and read the file
            $file = fopen($file_path, 'r');
            $header = fgetcsv($file);

            // Generate a column mapping based on case-insensitive CSV headers
            $column_mapping = $this->generateColumnMapping($header);

            // Loop through each row in the CSV file
            while ($columns = fgetcsv($file)) {
                if (empty($columns[0])) {
                    continue; // Skip empty rows
                }

                // Check if the number of columns matches the number of mappings
                if (count($column_mapping) !== count($columns)) {
                    Log::warning('Column count mismatch', [
                        'column_mapping' => $column_mapping,
                        'columns' => $columns
                    ]);
                    continue; 
                }

                // Create an associative array by combining column mapping with CSV values
                $data = array_combine($column_mapping, $columns);

                // Create a new EventTable instance
                $event = new EventTable();

                // Set member attributes from the CSV data
                $event->cast_name = $data['cast_name']; // Correctly using 'cast_name'
                $event->main_cast_name = $data['main_cast_name'];
                $event->is_translated = $data['is_translated'];
                $event->type_of_control = $data['type_of_control'];
                $event->channel_name = $data['channel_name'];
                $event->upload_date = Carbon::createFromFormat('d/m/Y', $data['upload_date'])->format('Y-m-d');
                $event->play_date = Carbon::createFromFormat('d/m/Y', $data['play_date'])->format('Y-m-d');
                $event->start_time = $data['start_time'];
                $event->end_time = $data['end_time'];

                // Convert start_time and end_time to H:i:s format
                try {
                    $event->start_time = Carbon::createFromFormat('H:i', $event->start_time)->format('H:i:s');
                    $event->end_time = Carbon::createFromFormat('H:i', $event->end_time)->format('H:i:s');
                } catch (\Exception $e) {
                    Log::error('Time format error', ['error' => $e->getMessage(), 'row' => $columns]);
                    continue; // Skip rows with invalid time formats
                }

                // Get the duration depending on the type of control
                $durationConfig = $this->getDurationConfig($event->type_of_control);

                // Set the duration attribute
                $event->duration = $durationConfig ? $durationConfig->value : null;

                // Calculate the end_date by adding the duration to the play_date
                if ($event->duration) {
                    $event->end_date = Carbon::createFromFormat('Y-m-d', $event->play_date)
                        ->addDays($event->duration)
                        ->format('Y-m-d');
                } else {
                    $event->end_date = null;
                }

                // Save the event
                $event->save();
            }

            // Close the file
            fclose($file);

            // Redirect to the dashboard with a success message
            return redirect()->back()->with('success', 'CSV file uploaded successfully');
        } catch (QueryException $e) {
            // Handle integrity constraint violations
            if ($e->getCode() === 23000) {
                return redirect()->back()->with('error', 'Integrity constraint violation. Please check your data.');
            }

            // Handle other database errors
            return redirect()->back()->with('error', 'An error occurred while uploading programs.');
        } catch (\Exception $e) {
            // Handle unexpected exceptions
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Generate a column mapping based on case-insensitive CSV headers
     *
     * @param array $csvHeaders
     * @return array
     */
    public function generateColumnMapping($csvHeaders)
    {
        // Define the expected column names from the EventTable
        $expectedColumns = [
            'Movie/Music_name' => 'cast_name',
            'Producer/Artist' => 'main_cast_name',
            'Is_Translated' => 'is_translated',
            'Type_Of_Control' => 'type_of_control',
            'Channel_Name' => 'channel_name',
            'Upload_Date' => 'upload_date',
            'Play_Date' => 'play_date',
            'Start_Time' => 'start_time',
            'End_Time' => 'end_time'
        ];

        $mapping = [];
        foreach ($csvHeaders as $csvHeader) {
            $csvHeaderLower = strtolower($csvHeader);
            foreach ($expectedColumns as $csvKey => $tableColumn) {
                if (strtolower($csvKey) === $csvHeaderLower) {
                    $mapping[] = $tableColumn;
                    break;
                }
            }
        }

        return $mapping;
    }

    /**
     * Retrieve the duration configuration based on the type of control
     *
     * @param string $typeOfControl
     * @return \App\Models\Configuration|null
     */
    private function getDurationConfig($typeOfControl)
    {
        $configKey = strtolower($typeOfControl) === 'movie' ? 'movie_repeat' : 'music_repeat';
        return Configuration::where('key', $configKey)->first();
    }
}
