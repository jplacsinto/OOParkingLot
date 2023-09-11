<?php

namespace App\Repository;
use Illuminate\Database\Eloquent\Model;

interface ParkingRepositoryInterface extends EloquentRepositoryInterface {
    public function hasActiveParking(string $registrationId): bool;
    public function lastUnParking(string $registrationId): ?Model;
}