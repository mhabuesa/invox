<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\EnvatoHelper;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InstallationController extends Controller
{
    public function install()
    {
        // Logic for installation page
        return view('installation.install');
    }

    public function verifyLicense(Request $request)
    {
        Log::info('License request received', $request->all());

        $request->validate([
            'purchase_code' => 'required',
        ]);

        $license = EnvatoHelper::verifyPurchase($request->purchase_code);

        if ($license && isset($license['item']['name'])) {
            return response()->json([
                'success' => true,
                'message' => 'License verified for: ' . $license['item']['name'],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid purchase code.',
        ]);
    }

    public function check_db(Request $request)
    {
        $host = 'localhost';
        $database = $request->db_name;
        $username = $request->db_user;
        $password = $request->db_pass;

        config([
            'database.connections.temp' => [
                'driver' => 'mysql',
                'host' => $host,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        try {
            SettingsHelper::setEnvironmentValue('DB_DATABASE', $database);
            SettingsHelper::setEnvironmentValue('DB_USERNAME', $username);
            SettingsHelper::setEnvironmentValue('DB_PASSWORD', $password);
            DB::connection('temp')->getPdo();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
