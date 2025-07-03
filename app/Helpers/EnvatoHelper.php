<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class EnvatoHelper
{
    public static function verifyPurchase($purchaseCode)
    {
        // Check if app is running in local/dev environment
        if (app()->environment('local')) {
            // Return mock response for testing
            if ($purchaseCode === '1279') {
                return [
                    'item' => [
                        'name' => 'Invox Laravel Invoice Script',
                        'id' => 12345678,
                    ],
                    'buyer' => 'test_buyer',
                    'license' => 'Regular License',
                    'supported_until' => now()->addMonths(6)->toDateString(),
                ];
            }

            // Invalid test code
            return false;
        }

        // Production: real API call
        $token = env('ENVATO_PERSONAL_TOKEN');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'User-Agent' => 'LaravelApp'
        ])->get("https://api.envato.com/v3/market/author/sale", [
            'code' => $purchaseCode
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return false;
    }
}
