<?php

namespace Database\Seeders;

use App\Models\Hotels;
use App\Models\Rooms;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   
        $hotels = Hotels::all();

    //Seeds the hotels table with initial data and used the relationship with Hotels model to access its id and name.
        foreach ($hotels as $hotel) {
            for ($i = 1; $i <= 5; $i++) {
                Rooms::create([
                    'hotels_id' => $hotel->id,
                    'room_name' => $hotel->hotel_name. ' Room '.$i,
                    'capacity'  => rand(2, 4),
                    'rate'      => rand(80, 250),
                ]);
            }
        }
    }
}
