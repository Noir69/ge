<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'paid',
        'status'
    ];

      //Defined relationships with other models
    public function paymentsReserve(){
        return $this->belongsTo(Reservations::class, 'reservations_id');
    }
}
