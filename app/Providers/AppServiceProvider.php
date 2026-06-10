<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('manage-master-data', function ($user) {
            return $user->role->level >= 4;
        });

        Gate::define('is-operator', function ($user) {
            return $user->role->level == 0;
        });

        Gate::define('is-approval', function ($user) {
            return in_array($user->role->level, [1, 2, 3]);
        });

        Gate::define('is-bendahara', function ($user) {
            return $user->role->level == 4;
        });
    }
}
