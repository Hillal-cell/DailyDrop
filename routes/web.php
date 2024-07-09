<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\EventTable;
use App\Http\Middleware\LogAudit;





Route::get('/', function () {
    $version = exec('git describe --tags');
    return view('welcome',['version' => $version]);
});

Route::get('/dashboard', function () {

     // Fetch distinct channel names from the events table
     $channels = EventTable::distinct()->pluck('channel_name');
     

      // Check if there are any flash messages
      if(session()->has('error')) {
        // Flash error message to the session
        session()->flash('error', session('error'));
    }

    if(session()->has('success')) {
        // Flash success message to the session
        session()->flash('success', session('success'));
    }
    
    

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
    Route::patch('/update-event/{castName}', [ProfileController::class, 'updateEvent'])->name('profile.updateEvent');
    Route::get('/get-Movieduration',[ProfileController::class,'getMovieDuration'])->name('getMovieDuration');
    Route::get('/get-Musicduration',[ProfileController::class,'getMusicDuration'])->name('getMusicDuration');
    Route::get('/guidelines',[ProfileController::class,'getGuidelines'])->name('guidelines');


    

});

Route::middleware(['auth','log.audit','admin'])->group(function () {
    Route::get('/report', [ProfileController::class, 'getReport'])->name('report');
    Route::get('/configuration', [ProfileController::class, 'getConfigurations'])->name('configuration');
    Route::patch('/configuration', [ProfileController::class, 'updateConfigurations'])->name('configuration.update');
    Route::get('/get-users', [ProfileController::class, 'getUsers'])->name('getUsers');
    Route::get('/get-user/{id}', [ProfileController::class, 'updateUser'])->name('getUser');
    Route::PATCH('/get-user/{id}', [ProfileController::class, 'updateUser'])->name('updateUser');
    Route::get('/get-logs', [ProfileController::class, 'getLogs'])->name('getLogs');

    Route::get('/env', function() {
        return app()->environment();
    });
    
});


require __DIR__.'/auth.php';
