<?php

namespace App\Service\Parking;

interface ParkingServiceInterface{

    public function park(array $parakingData) : bool;
    public function unpark(int $id) : bool;
}