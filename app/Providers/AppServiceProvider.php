<?php

namespace App\Providers;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProductTransferRepositoryInterface;
use App\Repositories\Implementations\OrderRepository;
use App\Repositories\Implementations\ProductRepository;
use App\Repositories\Implementations\ProductTransferRepository;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\StatisticsServiceInterface;
use App\Services\Implementations\ProductService;
use App\Services\Implementations\StatisticsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(StatisticsServiceInterface::class, StatisticsService::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductServiceInterface::class, ProductService::class);
        $this->app->singleton(ProductTransferRepositoryInterface::class, ProductTransferRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
