<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\Parking\ParkingService;
use App\Service\Parking\ParkingServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ParkingServiceInterface::class, ParkingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
