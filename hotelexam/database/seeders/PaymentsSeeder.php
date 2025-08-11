<?php

namespace Database\Seeders;

use App\Models\Payments;
use App\Models\Reservations;
use App\Status;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Seeds the payments table with initial data
        //This initializes the reservations collection.
         $reservations = Reservations::all();
        if ($reservations->isEmpty()) {
            return;
        }

        $today = Carbon::today();

        foreach ($reservations as $reservation) {
            $checkIn  = Carbon::parse($reservation->check_in);
            $checkOut = Carbon::parse($reservation->check_out);

            // Choose status using App\Status based on timing
            if ($checkOut->lt($today)) {
                // Past reservation: mostly COMPLETED, sometimes REFUNDED
                $status = (mt_rand(1, 100) <= 80) ? Status::COMPLETED : Status::REFUNDED;
            } elseif ($checkIn->lte($today) && $checkOut->gte($today)) {
                // Ongoing reservation: PENDING
                $status = Status::PENDING;
            } else {
                // Future reservation: usually PENDING, occasionally FAILED
                $status = (mt_rand(1, 100) <= 90) ? Status::PENDING : Status::FAILED;
            }

            // Checks if the status is completed.
            $paid = match ($status) {
                Status::COMPLETED => (float) $reservation->cost,
                Status::REFUNDED  => 0.00,
                Status::PENDING   => 0.00,
                Status::FAILED    => 0.00,
            };

            Payments::create([
                'reservations_id' => $reservation->id,
                'paid'            => $paid,
                // Store enum value into DB column
                'status'          => $status->value,
            ]);
        }
    }
}
