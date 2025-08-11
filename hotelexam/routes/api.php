<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::get('/user', )->middleware('auth:sanctum');






//  /api/hotels/{hotel}/available-rooms
//  POST /api/reservations
//   /api/reservations/{reservation}