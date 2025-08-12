<?php

namespace App\Http\Controllers;

use App\Models\Hotels;
use App\Models\Payments;
use App\Models\Reservations;
use App\Models\Rooms;
use App\Models\User;
use App\Rules\AvailableRoom;
use App\Status;
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
      return view('reservation.create');
   }

   //Method to store a reservation
   //I would how wanted to test this with a form but I could not get the front end to work properly.
   public function store(Request $request){
      $room = Reservations::findOrFail($request->rooms_id)
                ->lockForUpdate();

      // users_id',
      //   'rooms_id',
      //   'guest_count',
      //   'check_in',
      //   'check_out',
      //   'cost',
      //   'canceled'
      //Validating the request data
      $request->validate([
            'users_id' => 'required|integer|exists:users,id',
            //For validation of rooms if they are available, I would like to implement it at the form. 
            'rooms_id' => ['required|integer|exists:rooms,id', new AvailableRoom()],
            'guest_count' => 'required|integer|min:1|max:4',
            'check_in' => 'required|date_format:H:i|after_or_equal:today',
            'check_out' => 'required|date_format:H:i|after:check_in',
            'cost' => 'required|decimal:8,2',
      ]);
      //Assigning the values to the reservation model
      if ($request) {
         $reservation = new Reservations();
         $payment = new Payments();
         $reservation->users_id = $request->users_id;
         $reservation->rooms_id = $request->rooms_id;
         $reservation->guest_count = $request->guest_count;
         $reservation->check_in = $request->check_in;
         $reservation->check_out = $request->check_out;
         $reservation->cost = $request->cost;
         //I forgot to put a soft delete so I accidentally made a manual boolean column to check if the reservation is canceled.
         if ($reservation->save()) {
            //If the reservation saved successfully, we save the payment.
            $payment->reservations_id = $reservation->id;
            $payment->status = Status::PENDING->value; // Assuming you have a Status enum defined
            return redirect()->route('welcome')->with('success', 'Reservation created successfully!');
         } else {
            return redirect()->back()->with('error', 'Failed to create reservation.');
         }
      }
   }

   //Method to view a reservation
   public function show($id){
      //Initializing the reservation and payment models
      $reservation = Reservations::findOrFail($id);
      $payment = Payments::where('reservations_id', $id)->first();

      if ($reservation || $payment) {
         return view('reservation.show', compact('reservation', 'payment'));
      }
      return redirect()->back()->with('error', 'Reservation or payment not found.');
      

   }

   //Method to cancel a reservation
   public function cancel($id){
      //Initializing the reservation and payment models
      $reservation = Reservations::findOrFail($id);
      $payment = Payments::where('reservations_id', $id)->first();

      //This is how I validate in the past, but for some reason the validate() function was getting errors.
      // $this->validate($reservation, [
      //    'canceled' => 'required|boolean',
      // ]);
      // $this->validate($payment, [
      //    'status' => 'required|in:pending,completed,canceled,failed',
      // ]);

      //Validating the reservation and payment columns.
      //This errors out 
      // $reservation->validate([
      //    'canceled' => 'required|boolean',
      // ]);
      // $payment->validate([
      //    'status' => 'required|in:pending,completed,canceled,failed',
      // ]);

      //If the reservation and payment exist, update their status
      if ($reservation && $payment) {
         $reservation->canceled = true;
         $payment->status = Status::FAILED->value;
         $payment->save();
         $reservation->save();
         //It should redirect to the welcome page with a success message and flash it if the front end has the code for it.
         return redirect()->route('welcome')->with('success', 'Reservation canceled successfully!');
      }
   }

   //Method to view a reservation by its ID
   public function viewReserve($id){
      return Reservations::with('reserveRooms', 'reservePayments', 'reserveUser')
         ->where('id', $id)
         ->firstOrFail();
   }

   //Method to view available rooms in a hotel
   public function viewAvailableRooms($id){
      return Rooms::with('roomsReserve')
         ->where('hotels_id', $id)
         ->whereDoesntHave('roomsReserve', function (Builder $query) {
            $query->where('check_in', '<=', now())
                  ->where('check_out', '>=', now());
         })
         ->get();
      }
}