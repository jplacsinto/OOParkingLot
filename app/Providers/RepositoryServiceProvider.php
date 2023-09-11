<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\EntrypointRepository;
use App\Repository\Eloquent\ParkingRepository;
use App\Repository\Eloquent\SlotRepository;
use App\Repository\EloquentRepositoryInterface;
use App\Repository\EntrypointRepositoryInterface;
use App\Repository\ParkingRepositoryInterface;
use App\Repository\SlotRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(EntrypointRepositoryInterface::class, EntrypointRepository::class);
        $this->app->bind(ParkingRepositoryInterface::class, ParkingRepository::class);
        $this->app->bind(SlotRepositoryInterface::class, SlotRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
