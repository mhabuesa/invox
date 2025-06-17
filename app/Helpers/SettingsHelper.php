<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;

class SettingsHelper
{
    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (is_bool(env($envKey))) {
            $oldValue = var_export(env($envKey), true);
        } else {
            $oldValue = env($envKey);
        }

        // Wrap value in quotes and escape quotes
        $envValue = '"' . addslashes($envValue) . '"';

        if (strpos($str, $envKey) !== false) {
            $str = preg_replace("/^{$envKey}=.*$/m", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }

        file_put_contents($envFile, $str);

        // Artisan::call('config:clear');
        // Artisan::call('cache:clear');

        return $envValue;
    }

    public static function formatPermission($text)
    {
        return ucwords(str_replace('_', ' ', $text));
    }
}
