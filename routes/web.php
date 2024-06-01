<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\EventTable;
use App\Http\Middleware\LogAudit;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

     // Fetch distinct channel names from the events table
     $channels = EventTable::distinct()->pluck('channel_name');
     
        
     // Pass the channels to the view
     return view('dashboard', ['channels' => $channels]);
})->middleware(['auth', 'verified','log.audit'])->name('dashboard');

Route::middleware('auth','log.audit')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/save-event', [ProfileController::class, 'saveEvent'])->name('profile.saveEvent');
    Route::get('/get-events', [ProfileController::class, 'getEvents'])->name('profile.getEvents');
    Route::get('/get-cast/{castName}', [ProfileController::class, 'getCast'])->name('profile.getCast');
    Route::get('/channel/{channelName}/calendar', [ProfileController::class, 'showChannelCalendar'])->name('channel.calendar');
    Route::get('/channel/{channelName}/events', [ProfileController::class, 'getChannelEvents']);
    Route::get('/report',[ProfileController::class,'getReport'])->name('report');
    Route::patch('/update-event/{castName}', [ProfileController::class, 'updateEvent'])->name('profile.updateEvent');



    

});




require __DIR__.'/auth.php';
