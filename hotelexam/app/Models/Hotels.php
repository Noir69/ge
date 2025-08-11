<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'hotel_name'
    ];

    //Defined relationships with other models
    public function hotelRooms(){
        return $this->hasMany(Rooms::class, 'hotels_id');
    }
}
