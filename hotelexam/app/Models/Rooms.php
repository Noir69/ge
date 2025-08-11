<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotels_id',
        'room_name',
        'capacity',
        'rate'
    ];

      //Defined relationships with other models
    public function roomsHotel(){
        return $this->belongsTo(Hotels::class, 'hotels_id');
    }
    public function roomsReserve(){
        return $this->hasMany(Reservations::class, 'rooms_id');
    }

    // A placeholder for the search scope 
     public function scopeSearchrooms($query,$search = ''){
        
        $today = Carbon::today();
        // Include scope for available rooms 
        $query->whereDoesntHave('roomsReserve', function ($q) use ($today) {
            $q->where('check_out', '>=', $today);
        });
    }
}
