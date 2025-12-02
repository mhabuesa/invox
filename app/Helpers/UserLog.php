<?php

use App\Models\UserLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

if (!function_exists('userLog')) {
    function userLog($action, $description = null)
    {
        $userId = Auth::id();

        if ($userId) {
            UserLog::create([
                'user_id' => $userId,
                'action' => $action,
                'description' => $description,
            ]);
        }
    }
}
