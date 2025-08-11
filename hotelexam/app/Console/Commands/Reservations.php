<?php

namespace App\Console\Commands;

use App\Status;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Reservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans up unpaid reservations that is older than 24 hrs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $ifold = Carbon::today()->subHours(24);
        //Logic to clean up reservations
        $unpaidReservations = \App\Models\Payments::where('status', 'pending')
        ->where('created_at', '<=', $ifold)->get();
        foreach ($unpaidReservations as $pay) {
            //Cancel the reservation
            $pay->status = Status::FAILED->value;
            $pay->save();
        }
    }
}
