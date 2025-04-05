<?php

namespace App\Providers;

use App\Interfaces\Repositories\TravelOrderRepositoryInterface;
use App\Interfaces\Services\TravelOrderServiceInterface;
use App\Repositories\TravelOrderRepository;
use App\Services\TravelOrderService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TravelOrderRepositoryInterface::class,
            TravelOrderRepository::class
        );
    
        $this->app->bind(
            TravelOrderServiceInterface::class,
            TravelOrderService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
