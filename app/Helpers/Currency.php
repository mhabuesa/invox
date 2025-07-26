<?php

use App\Models\Currency;

if (!function_exists('currency')) {
    function currency($amount = null): string
    {
        $symbol = Currency::where('status', 1)->first()?->symbol ?? '$';

        if (!is_numeric($amount)) {
            return $symbol;
        }

        return $symbol . $amount;
    }
}
