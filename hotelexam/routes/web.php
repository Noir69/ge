<?php

use App\Http\Controllers\ReservationController;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Right now, this looks scuffed in the view, but it displays all the reservations as well as the users.
Route::get('/', function () {
    $pa = Reservations::with('reserveRooms','reservePayments')->paginate(5);
     $us = User::all();
    return view('welcome' ,compact('pa', 'us'));
})->name('welcome');

//Isn't used.
Route::get('/login', function () {
    return "login bro";
})->name('login');

//Trying different routes that be used
Route::post('api/reserve', [ReservationController::class, 'store'])->name('reserve.room');

//I'm not gonna group the routes as I want to keep them simple for now, as well as the fact that I don't have any middleware.
Route::get('/api/reservations/{id}', [ReservationController::class, 'viewReserve'])->name('view.reserve');
Route::get('/api/hotels/{id}/available-rooms', [ReservationController::class, 'viewAvailableRooms'])->name('view.availableRooms');
Route::get('/api/cancel/{id}', [ReservationController::class, 'cancel'])->name('cancel.reserve');


// Route::get('/view', function ($pa, $us) {
//     return view('welcome2' ,compact('pa', 'us'));
// })->name('view');
