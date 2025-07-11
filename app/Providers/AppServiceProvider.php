<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
        // Define a gate for super admin access
        Gate::before(function ($user, $ability) {
            if ($user->is_super_admin) {
                return true;
            }
        });

        // Automatically create .env from .env.example if missing
        if (!file_exists(base_path('.env'))) {
            if (file_exists(base_path('.env.example'))) {
                File::copy(base_path('.env.example'), base_path('.env'));
            }
        }
    }
}
