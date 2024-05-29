<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\EventTable;
use Carbon\Carbon;


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
             'is_translated' => 'required|in:yes,no',
             'type_of_control' => 'required|in:Music,Movie',
             'channel_name' => 'required|string|max:255',
             'duration' => 'required|integer|min:1',
             'upload_date' => 'required|date_format:Y-m-d',
             'play_date' => 'required|date_format:Y-m-d',
             'end_date' => 'required|date_format:Y-m-d',
         ]);
     
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
         return response()->json(['success' => true]);
     }
     

    public function getEvents(Request $request)
    {
        // Retrieve events data from the database
        $events = EventTable::all()->map(function ($event) {
            return [
                'title' => $event->cast_name,
                'start' => $event->upload_date,
                'description' => $event->main_cast_name,
                // 'end' => $event->end_date,
                'channel_name' => $event->channel_name,
                'editable' => true,
                'allDay' => true,
                
                // Add other fields as needed
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
            // Transform the cast data as needed
            $castData = [
                'cast_name' => $cast->cast_name,
                'main_cast_name' => $cast->main_cast_name,
                'is_translated' => $cast->is_translated,
                'type_of_control' => $cast->type_of_control,
                'channel_name' => $cast->channel_name,
                'duration' => $cast->duration,
                'upload_date' => $cast->upload_date,
                'play_date' => $cast->play_date,
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
   public function updateEvent(Request $request, $castName): JsonResponse
{
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
        'is_translated' => 'required|in:yes,no',
        'type_of_control' => 'required|in:Music,Movie',
        'channel_name' => 'required|string|max:255',
        'upload_date' => 'required|date_format:Y-m-d',
        'duration' => 'required|integer|min:1',
        'play_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d',
        // Add validation rules for other fields as needed
    ]);

    // Update the event record with the validated data
    $event->update($validatedData);

    // Return a success response
    return response()->json(['success' => true]);
}


public function showChannelCalendar($channelName)
{

     // Fetch distinct channel names from the events table
     $channelNames = EventTable::distinct()->pluck('channel_name');

     // Pass both the selected channel name and the list of all channel names to the view
     return view('channelContent', ['channelName' => $channelName, 'channels' => $channelNames]);
 }

public function getChannelEvents($channelName)
{
    $events = EventTable::where('channel_name', $channelName)->get();

    $formattedEvents = $events->map(function ($event) {
        return [
            'title' => $event->cast_name,
            'start' => $event->upload_date,
            'end' => $event->end_date, // Ensure 'end_date' is present in your events
        ];
    });

    return response()->json($formattedEvents);
}



public function getReport(){
    return view('report');
}







}
