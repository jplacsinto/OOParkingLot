<?php

namespace App\Repository\Eloquent;

use App\Models\Slot;
use App\Repository\SlotRepositoryInterface;

class SlotRepository extends BaseRepository implements SlotRepositoryInterface
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
    public function __construct(Slot $model)
    {
        $this->model = $model;
    }
}