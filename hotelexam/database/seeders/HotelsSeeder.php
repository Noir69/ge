<?php

namespace Database\Seeders;

use App\Models\Hotels;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    //Seeds the hotels table with initial data
    public function run(): void
    {
        $names = [
            'Grand Plaza',
            'Seaside Resort',
            'Mountain Lodge',
        ];

        foreach ($names as $name) {
            Hotels::create(['hotel_name' => $name]);
        }
    }
}
