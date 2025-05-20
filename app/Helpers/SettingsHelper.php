<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;

class SettingsHelper
{
    public static function updateAppSettings($data)
    {
        // Update .env file with new values
        Artisan::call("env:set APP_NAME='{$data->company_name}'");
        Artisan::call("env:set APP_DEBUG='{$data->debug_mode}'");
        Artisan::call("env:set APP_URL='{$data->app_url}'");
        Artisan::call("env:set TIME_ZONE='{$data->time_zone}'");

        // Update application configuration in runtime
        config(['app.name' => $data->company_name]);
        config(['app.url' => $data->app_url]);
        config(['app.debug' => $data->debug_mode === 'true']);
        config(['app.timezone' => $data->time_zone]);

        // Optional: Clear config cache to make .env changes effective
        Artisan::call('config:clear');
    }
}
