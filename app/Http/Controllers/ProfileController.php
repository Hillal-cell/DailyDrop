<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\EventTable;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    /**
        * Handle save activity
     */

     public function saveEvent(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'cast_name' => 'required|string|max:255',
        'main_cast_name' => 'required|string|max:255',
        'is_translated' => 'required|in:not_applicable,yes,no',
        'type_of_control' => 'required|in:Music,Movie',
        'channel_name' => 'required|string|max:255',
        'duration' => 'required|integer|min:1',
        'upload_date' => 'required|date_format:Y-m-d',
        'play_date' => 'required|date_format:Y-m-d',
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
        'end_date' => 'required|date_format:Y-m-d',
        // Add validation rules for other fields as needed
    ]);

    // Convert start_time and end_time to H:i:s format
    $validatedData['start_time'] = Carbon::createFromFormat('H:i', $validatedData['start_time'])->format('H:i:s');
    $validatedData['end_time'] = Carbon::createFromFormat('H:i', $validatedData['end_time'])->format('H:i:s');

    // Check if an event with the same details already exists
    $existingEvent = EventTable::where('cast_name', $validatedData['cast_name'])
        ->where('main_cast_name', $validatedData['main_cast_name'])
        ->where('channel_name', $validatedData['channel_name'])
        ->first();

    if ($existingEvent) {
        $currentDate = date('Y-m-d');
        if ($currentDate < $existingEvent->end_date) {
            return response()->json(['success' => false, 'message' => 'An event with these details already exists and has not yet ended.'], 400);
        }
    }

    // Create a new event record
    $event = EventTable::create($validatedData);

    // Return a success response
    return response()->json(['success' => true, 'message' => 'Event has been successfully saved.'], 200);
}


     public function getEvents(Request $request)
     {
         // Retrieve events data from the database
         $events = EventTable::all()->map(function ($event) {
             // Combine upload_date with start_time and end_time and convert to ISO 8601 format
             $start_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $event->upload_date . ' ' . $event->start_time)->toIso8601String();
             $end_datetime = Carbon::createFromFormat('Y-m-d H:i:s', $event->upload_date . ' ' . $event->end_time)->toIso8601String();
     
            
             return [
                 'title' => $event->cast_name,
                 'start' => $start_datetime,
                 'end' => $end_datetime,
                 'description' => $event->main_cast_name,
                 'channel_name' => $event->channel_name, 
                 'typeOfControl'=>$event->type_of_control,
                 'editable' => true,
                 'allDay' => false,   
                 'color' => 'blue',
                 'backgroundColor' => 'green',
             ];
         });
     
         // Return events data as JSON response
         return response()->json($events);
     }
     

     

     public function getCast($castName)
     {
         $cast = EventTable::where('cast_name', $castName)->first();
     
         // Check if cast data exists
         if ($cast) {
             // Transform the cast data as needed, formatting the time fields
             $castData = [
                 'cast_name' => $cast->cast_name,
                 'main_cast_name' => $cast->main_cast_name,
                 'is_translated' => $cast->is_translated,
                 'type_of_control' => $cast->type_of_control,
                 'channel_name' => $cast->channel_name,
                 'duration' => $cast->duration,
                 'upload_date' => $cast->upload_date,
                 'play_date' => $cast->play_date,
                 'start_time' => \Carbon\Carbon::createFromFormat('H:i:s', $cast->start_time)->format('H:i'),
                 'end_time' => \Carbon\Carbon::createFromFormat('H:i:s', $cast->end_time)->format('H:i'),
                 'end_date' => $cast->end_date,
                 // Add other fields as needed
             ];
     
             // Return the cast data as JSON response
             return response()->json($castData);
         } else {
             // Return a not found response if cast data does not exist
             return response()->json(['error' => 'Cast not found'], 404);
         }
     }
     


     /**
     * Update the event information.
     */
    public function updateEvent(Request $request, $castName)
{
    try {
        // Find the event record by its cast_name
        $event = EventTable::where('cast_name', $castName)->first();
        
        // Check if the event record exists
        if (!$event) {
            // Return a not found response if event does not exist
            return response()->json(['error' => 'Event not found'], 404);
        }
        
        // Validate the request data
        $validatedData = $request->validate([
            'cast_name' => 'required|string|max:255',
            'main_cast_name' => 'required|string|max:255',
            'is_translated' => 'required|in:not_applicable,yes,no',
            'type_of_control' => 'required|in:Music,Movie',
            'channel_name' => 'required|string|max:255',
            'upload_date' => 'required|date_format:Y-m-d',
            'duration' => 'required|integer|min:1',
            'play_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        // Convert start_time and end_time to H:i:s format
        $validatedData['start_time'] = Carbon::createFromFormat('H:i', $validatedData['start_time'])->format('H:i:s');
        $validatedData['end_time'] = Carbon::createFromFormat('H:i', $validatedData['end_time'])->format('H:i:s');

        // Calculate end date based on other fields
        $endDate = Carbon::createFromFormat('Y-m-d', $validatedData['play_date'])
        ->addDays((int)$validatedData['duration']);

        // Add end date to validated data
        $validatedData['end_date'] = $endDate->format('Y-m-d');

        // Log the validated data for debugging
        Log::info('Validated data:', $validatedData);

        // Update the event record with the validated data
        $event->update($validatedData);

        // Return a success response
        return response()->json(['success' => true])->with('success','The event has been successfully updated');
    } catch (\Exception $e) {
        // Log the exception message for debugging
        Log::error('Error updating event: ' . $e->getMessage());

        // Return an error response
        return response()->json(['error' => 'Failed to update event'], 500)->with('error','Failed to update event.');
    }
}
    /**
     * Display the calendar for a specific channel.
     */
    public function showChannelCalendar($channelName)
    {

        // Fetch distinct channel names from the events table
        $channelNames = EventTable::distinct()->pluck('channel_name');

        // Pass both the selected channel name and the list of all channel names to the view
        return view('channelContent', ['channelName' => $channelName, 'channels' => $channelNames]);
    }

    /**
     * Get the events for a specific channel.
     */
    public function getChannelEvents($channelName)
    {
        $events = EventTable::where('channel_name', $channelName)->get();

        

        $formattedEvents = $events->map(function ($event) {

            return [
                'title' => $event->cast_name,
                'start' => $event->upload_date.'T'.$event->start_time,
                'allDay' => false,
                
                
            ];
        });

        return response()->json($formattedEvents);
    }



    public function getConfigurations(){
        return view('configurations');
    }

    public function getGuidelines(){
        return view('guidelines');
    }

    public function getReport(){
        return view('report');
    }


    public function updateConfigurations(Request $request){
        $validatedData =$request->validate([
            'movie_repeat'=>'required|integer|min:180',
            'music_repeat'=>'required|integer|min:7',
        ]);
    
        // Check if the values meet the criteria
        // if ($validatedData['movie_repeat'] > 0 && $validatedData['music_repeat'] > 0) {
            // Add the user_id to the validated data
            $userId = auth()->id();
            // Loop through the validated data and create or update each configuration
            foreach ($validatedData as $key => $value) {
                // Find the configuration with the given key for the current user
                $config = Configuration::where('key', $key)->where('user_id', $userId)->first();
                if ($config) {
                    // Update the existing configuration record
                    $config->update(['value' => $value]);
                } else {
                    // Create a new configuration record
                    Configuration::create([
                        'key' => $key,
                        'value' => $value,
                        'user_id' => $userId
                    ]);
                }
            }
            return Redirect::route('configuration')->with('status', 'Configurations updated successfuly.');
       
    }   




    public function getMovieDuration(Request $request){
        $movieDuration = Configuration::where('key', 'movie_repeat')->first();
        return response()->json(['movie_duration' => $movieDuration->value]);
    }

    public function getMusicDuration(Request $request){
        $musicDuration = Configuration::where('key', 'music_repeat')->first();
        return response()->json(['music_duration' => $musicDuration->value]);
    }


    public function getUsers(){
        $users = User::all(); 
        return view('users', compact('users')); 
    }



    public function updateUser(Request $request, $id){


         // If the request is a GET request, render the edit form
         if ($request->isMethod('get')) {
            // Retrieve the user record by ID
            $user = User::find($id);

            // Check if the user record exists
            if (!$user) {
                return redirect()->route('getUsers')->with('error', 'User not found.');
            }

            // Return the edit form view with the user data
            return view('editUser', compact('user'));
        }



        // If the request is a POST request, handle the form submission
        if ($request->isMethod('patch')) {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'role' => 'required|string',
                
            ]);

            // Find the student record by ID
            $user = User::find($id);

            // Check if the student record exists
            if (!$user) {
                return redirect()->route('getUsers')->with('error', 'User not found.');
            }

            // Update the user record with the data from the form fields
            $user->update($validatedData);


            return redirect()->route('getUsers')->with('status', 'User record updated successfully.');
        }

    }
    
    public function getLogs(Request $request) {
        // Check if a search query parameter is present
        $searchTerm = $request->query('search');

        if (!empty($searchTerm)) {
            $logs = AuditLog::where('user_id', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('role', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('action', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('path', 'LIKE', "%{$searchTerm}%")
                            ->get();

            if ($logs->isEmpty()) {
                return redirect()->route('getLogs')->with('error', 'No logs found for the search term: ' . $searchTerm);
            }
        } else {
            $logs = AuditLog::all();
        }

        // Return the view with the logs (filtered or all)
        return view('logs', compact('logs'));
    }



    public function getgiude(){
        $events = EventTable::all()->map(function ($event) {
            return [
                'channel_name' => $event->channel_name,
                'cast_name' => $event->cast_name,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
            ];
        });


        $channels = EventTable::distinct()->pluck('channel_name');
        
        return view('guide', [
            'channels' => $channels,
            'events' => $events
        ]);
    }



    


}
