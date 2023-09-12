<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'entrypoint_id' => $this->entrypoint_id,
            'slot_id' => $this->slot_id,
            'size' => $this->slot->size,
            'registration_id' => $this->registration_id,
            'date_parked' => $this->date_parked,
            'date_unparked' => $this->date_unparked,
            'parking_time' => $this->parking_time,
            'total_fee' => $this->total_fee
        ];
    }
}
