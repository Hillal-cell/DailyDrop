<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/save-event', [ProfileController::class, 'saveEvent'])->name('profile.saveEvent');
    Route::get('/get-events', [ProfileController::class, 'getEvents'])->name('profile.getEvents');
    Route::get('/get-cast/{castName}', [ProfileController::class, 'getCast'])->name('profile.getCast');

    //try to change it to post and see
    
    Route::PUT('/update-event/{castName}', [ProfileController::class, 'updateEvent'])->name('profile.updateEvent');


    

});




require __DIR__.'/auth.php';
