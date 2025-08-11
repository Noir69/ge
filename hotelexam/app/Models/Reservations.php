<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
   use HasFactory;

    protected $fillable = [
        'users_id',
        'rooms_id',
        'guest_count',
        'check_in',
        'check_out',
        'cost',
        'canceled'
    ];

      //Defined relationships with other models
    public function reserveRooms(){
        return $this->belongsTo(Rooms::class, 'rooms_id');
    }
    public function reserveUser(){
        return $this->belongsTo(User::class, 'users_id');
    }
    public function reservePayments(){
        return $this->hasOne(Payments::class, 'reservations_id');
    }
}
