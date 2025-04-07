<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{BlogRepositoryInterface,

    PermissionRepositoryInterface,
    RoleRepositoryInterface,
    CouponRepositoryInterface,
    UserRepositoryInterface};

use App\Repositories\{
    PermissionRepository,
    RoleRepository,
    BlogRepository,
    CouponRepository,
    UserRepository,
    };

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
