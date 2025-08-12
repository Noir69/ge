<?php

namespace App\Rules;

use App\Models\Reservations;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableRoom implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
         if(Reservations::where('rooms_id', $value)
            ->where('canceled', false)
            ->where(function ($query) use ($value) {
                $query->whereBetween('check_in', [$value->check_in, $value->check_out])
                      ->orWhereBetween('check_out', [$value->check_in, $value->check_out])
                      ->orWhere(function ($q)  use ($value) {
                          $q->where('check_in', '<=', $value->check_in)
                            ->where('check_out', '>=', $value->check_out);
                      });
            })->exists()){
                $fail('The selected room is not available for the selected dates.');
            }
    }
}
