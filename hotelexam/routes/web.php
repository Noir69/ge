<?php

use App\Http\Controllers\ReservationController;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// 
Route::get('/', function () {
    $pa = Reservations::with('reserveRooms','reservePayments')->paginate(5);
     $us = User::all();
    return view('welcome' ,compact('pa', 'us'));
})->name('welcome');

Route::get('/login', function () {
    return "login bro";
})->name('login');

//Trying different routes that be used
Route::get('api/v/{id}', [ReservationController::class, 'index'])->name('view.something');
Route::get('/view', function ($pa, $us) {
    return view('welcome2' ,compact('pa', 'us'));
})->name('view');