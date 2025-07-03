<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\QueryException;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share data globally across all views
        try {
            $setting = Setting::first();
        } catch (QueryException $e) {
            $setting = null;
        }

        View::share('setting', $setting);
    }
}
