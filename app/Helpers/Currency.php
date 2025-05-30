<?php

use App\Models\Currency;

if (!function_exists('currency')) {
    function currency($amount): string
    {
        $symbol = Currency::where('status', 1)->first()?->symbol ?? '$';
        return $symbol . number_format($amount);
    }
}
