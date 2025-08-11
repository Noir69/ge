<?php

namespace Database\Seeders;

use App\Models\Reservations;
use App\Models\Rooms;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeds the reservations table with initial data
        //This initializes the rooms and users collections to be used for creating reservations.
        $rooms = Rooms::all();
        $users = User::inRandomOrder()->get();

        if ($rooms->isEmpty() || $users->isEmpty()) {
            return;
        }

        //I used random for almost all variables to simulate a varied dataset as well as 
        $makeReservation = function (Carbon $checkIn, Carbon $checkOut) use ($rooms, $users) {
            $room = $rooms->random();
            $user = $users->random();

            $nights = max(1, $checkIn->diffInDays($checkOut));
            $guestCount = min(rand(1, 4), (int)($room->capacity ?? 4));
            $cost = $nights * (float) $room->rate;

            return Reservations::create([
                'users_id'    => $user->id,
                'rooms_id'    => $room->id,
                'guest_count' => $guestCount,
                'check_in'    => $checkIn->toDateString(),
                'check_out'   => $checkOut->toDateString(),
                'cost'        => $cost,
            ]);
        };

        $today = Carbon::today();

        // 5 past
        for ($i = 0; $i < 5; $i++) {
            $start = $today->copy()->subDays(rand(7, 30));
            $end   = $start->copy()->addDays(rand(1, 5));
            $makeReservation($start, $end);
        }

        // 5 present
        for ($i = 0; $i < 5; $i++) {
            $start = $today->copy()->subDays(rand(0, 2));
            $end   = $today->copy()->addDays(rand(1, 4));
            $makeReservation($start, $end);
        }

        // 5 future
        for ($i = 0; $i < 5; $i++) {
            $start = $today->copy()->addDays(rand(3, 30));
            $end   = $start->copy()->addDays(rand(1, 6));
            $makeReservation($start, $end);
        }
    }
}
