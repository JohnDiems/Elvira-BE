<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Repositories Interfaces
use App\Interface\Repositories\UserRepositoryInterface;
use App\Interface\Repositories\StoreConfigRepositoryInterface;
use App\Interface\Repositories\CategoryRepositoryInterface;
use App\Interface\Repositories\ProductRepositoryInterface;
use App\Interface\Repositories\OrderRepositoryInterface;
use App\Interface\Repositories\ShiftLogRepositoryInterface;

// Concrete Repositories
use App\Repositories\UserRepository;
use App\Repositories\StoreConfigRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ShiftLogRepository;

// Services Interfaces
use App\Interface\Services\AuthServiceInterface;
use App\Interface\Services\StoreSetupServiceInterface;
use App\Interface\Services\ProductServiceInterface;
use App\Interface\Services\OrderServiceInterface;
use App\Interface\Services\ShiftServiceInterface;
use App\Interface\Services\DashboardServiceInterface;

// Concrete Services
use App\Services\AuthService;
use App\Services\StoreSetupService;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Services\ShiftService;
use App\Services\DashboardService;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Repositories Bindings
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StoreConfigRepositoryInterface::class, StoreConfigRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(ShiftLogRepositoryInterface::class, ShiftLogRepository::class);

        // Services Bindings
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(StoreSetupServiceInterface::class, StoreSetupService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(ShiftServiceInterface::class, ShiftService::class);
        $this->app->bind(DashboardServiceInterface::class, DashboardService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
