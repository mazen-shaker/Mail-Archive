<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Enums\RoleEnum as ROLE;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{


    Paginator::useBootstrapFive();

    $permissions = ['view-entities', 'view-departments', 'view-users'];

    foreach ($permissions as $permission) {
        Gate::define($permission, function (User $user) {
            return in_array($user->role_id, [ROLE::ADMIN->value]);
        });
    }
}
}
