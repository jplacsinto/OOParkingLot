<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Parking extends Model
{
    protected $fillable = [
        'date_parked',
        'date_unparked',
        'registration_id',
        'parking_time',
        'total_fee',
        'slot_id',
        'entrypoint_id'
    ];

    public function slot(): HasOne
    {
        return $this->hasOne(Slot::class, 'id', 'slot_id');
    }
}
