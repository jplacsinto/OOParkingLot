<?php

namespace App\Repository;

interface EntrypointRepositoryInterface extends EloquentRepositoryInterface {
    /**
     * @return Int
     */
    public function countAll(): Int;
}