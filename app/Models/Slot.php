<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Slot extends Model
{
    /**
     * Get the current occupant.
     */
    public function occupant(): HasOne
    {
        return $this->hasOne(Parking::class)->ofMany([
            'date_parked' => 'max',
        ], function (Builder $query) {
            $query->where('date_unparked', null);
        });
    }
}
