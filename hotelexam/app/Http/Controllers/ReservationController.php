<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Payments;
use App\Models\Reservations;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
   public function index(Request $request, $id){
      //These functions are supposed to be used to get the reservations of a user but as I could not get the 
      // front end to work properly with that I want it to happen, I haven't figured out if it does work by the user_ID

      // $pa = Reservations::whereHas('reserveRooms','reservePayments',function (Builder $query) use ($id) {
      //    $query->where('users_id','like', $id);
      // })->get()->paginate(5);


 /* 
      This code was an attempt to filter reservations by user_id but it was not working as expected. Even
      though it was written like this in the docs, it wasn't working for me.
      $pa = Reservations::whereHas('reserveRooms', function (Builder $query) use ($id) {
      //    $query->where('users_id', $id);
      // })
      // ->orWhereHas('reservePayments', function (Builder $query) use ($id) {
      //    $query->reserveUser('users_id', $id);
      // })
       ->paginate(5);
*/
     
      // I used with() to eager load the relationships and then filtered by user_id
      $pa = Reservations::with('reserveRooms','reservePayments','reserveUser')->where('users_id', 'like', $id)->paginate(5);


      $us = User::all();
      return redirect('/view')->with('pa', $pa)->with('us', $us);
      // return redirect('welcome',compact('pa','us'));
      // return $pa;
   }

   //Method for the form
   public function create(){

   }

   //Method to store a reservation
   public function store(){

   }

   //Method to view a reservation
   public function show($id){

   }

   //Method to cancel a reservation
   public function cancel($id){

   }
}
