<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\EnvatoHelper;
use App\Helpers\SettingsHelper;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\File;

class InstallationController extends Controller
{
    public function install()
    {
        if (file_exists(storage_path('installed.txt'))) {
            return redirect()->route('index');
        }
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

        // Create temporary connection
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

        $envPath = base_path('.env');
        $examplePath = base_path('.env.example');

        // Create .env if not exists
        if (!File::exists($envPath)) {
            File::copy($examplePath, $envPath);
        }

        try {
            // Test DB connection
            DB::connection('temp')->getPdo();

            // Migrate on temp connection
            Artisan::call('migrate', [
                '--database' => 'temp',
                '--force' => true,
            ]);

            // Save real DB credentials into .env
            SettingsHelper::setEnvironmentValue('DB_HOST', $host);
            SettingsHelper::setEnvironmentValue('DB_DATABASE', $database);
            SettingsHelper::setEnvironmentValue('DB_USERNAME', $username);
            SettingsHelper::setEnvironmentValue('DB_PASSWORD', $password);

            // Clear config cache so .env updates apply
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            // Purge cached MySQL connection
            DB::purge('mysql');

            // Reload configuration (important)
            config()->set('database.connections.mysql.host', $host);
            config()->set('database.connections.mysql.database', $database);
            config()->set('database.connections.mysql.username', $username);
            config()->set('database.connections.mysql.password', $password);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function admin_setup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Step 1: Run database seeder
        Artisan::call('db:seed');

        // Step 2: Create the initial Administrator user
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => true,
        ]);

        // Step 3: Save initial system email setting
        Setting::create([
            'email' => $request->email,
        ]);


        // Step 4: Create Super Admin role
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Step 5: Retrieve all existing permissions from the database
        $permissions = Permission::all();
        // Step 6: Sync all permissions to the 'super_admin' role.
        $role->syncPermissions($permissions);

        // Step 7: Assign the 'super_admin' role to the newly created user
        $admin->assignRole($role);

        // Step 8: CACHE CLEAR AND FINALIZATION STEPS
        // Clear general application caches (config, view) to finalize the setup environment
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        // Mark installation as complete by creating the 'installed' file
        file_put_contents(storage_path('installed.txt'), 'Installed on: ' . now());



        return response()->json(['status' => 'success']);
    }
}
