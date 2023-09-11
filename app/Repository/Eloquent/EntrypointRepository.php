<?php

namespace App\Repository\Eloquent;

use App\Models\Entrypoint;
use App\Repository\EntrypointRepositoryInterface;

class EntrypointRepository extends BaseRepository implements EntrypointRepositoryInterface
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
    public function __construct(Entrypoint $model)
    {
        $this->model = $model;
    }

    public function countAll(): Int
    {
        return $this->model->count();
    }
}