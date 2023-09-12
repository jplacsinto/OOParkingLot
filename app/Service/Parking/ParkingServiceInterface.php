<?php

namespace App\Service\Parking;

use Illuminate\Database\Eloquent\Model;

interface ParkingServiceInterface{

    public function park(array $parakingData) : ?Model;
    public function unpark(int $id) : ?Model;
}