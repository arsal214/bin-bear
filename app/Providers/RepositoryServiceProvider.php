<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\{BlogRepositoryInterface,
    ContactUsRepositoryInterface,
    HomepageRepositoryInterface,
    PermissionRepositoryInterface,
    PrivacyPolicyRepositoryInterface,
    RoleRepositoryInterface,
    ProductCategoryRepositoryInterface,
    ProductRepositoryInterface,
    TermConditionRepositoryInterface,
    AboutRepositoryInterface,
    UserRepositoryInterface};

use App\Repositories\{
    ContactUsRepository,
    HomepageRepository,
    PermissionRepository,
    PrivacyPolicyRepository,
    RoleRepository,
    ProductCategoryRepository,
    ProductRepository,
    BlogRepository,
    TermConditionRepository,
    AboutRepository,
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

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
