<?php

namespace App\Repository\Eloquent;

use App\Models\Parking;
use App\Repository\ParkingRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ParkingRepository extends BaseRepository implements ParkingRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Parking $model)
    {
        $this->model = $model;
    }

    public function hasActiveParking(string $registrationId): bool
    {
        $activeParkingCount = $this->model->where('registration_id', $registrationId)
            ->where('date_unparked', null)->count();
        return $activeParkingCount > 0;
    }

    public function lastUnParking(string $registrationId): ?Model
    {
        $lastUnParking = $this->model->where('registration_id', $registrationId)
            ->where('date_unparked', '!=', null)->latest('date_unparked')->first();
        return $lastUnParking;
    }
}