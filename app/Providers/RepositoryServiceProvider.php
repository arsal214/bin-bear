<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{BlogRepositoryInterface,
    BookingRepositoryInterface,
    CategoryRepositoryInterface,
    PermissionRepositoryInterface,
    RoleRepositoryInterface,
    CouponRepositoryInterface,
    UserRepositoryInterface, ZipCodeRepositoryInterface};

use App\Repositories\{
    PermissionRepository,
    RoleRepository,
    BlogRepository,
    BookingRepository,
    CategoryRepository,
    CouponRepository,
    UserRepository,
    ZipCodeRepository,
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
        $this->app->bind(ZipCodeRepositoryInterface::class, ZipCodeRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
