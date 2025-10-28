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
        if (file_exists(storage_path('installed'))) {
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

        if (!File::exists($envPath)) {
            File::copy($examplePath, $envPath);
        }

        try {
            // Try connecting to the default database (usually mysql)
            DB::connection('temp')->getPdo();

            // If successful, run the migrations
            Artisan::call('migrate', [
                '--database' => 'temp',
                '--force' => true, // Use this in production or to skip confirmation
            ]);
            SettingsHelper::setEnvironmentValue('DB_DATABASE', $database);
            SettingsHelper::setEnvironmentValue('DB_USERNAME', $username);
            SettingsHelper::setEnvironmentValue('DB_PASSWORD', $password);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function admin_setup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Change 'users' if table is different
            'password' => 'required|min:6|confirmed',
        ]);

        // Save admin user
        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_super_admin' => true,
        ]);

        Setting::create([
            'email' => $request->email,
        ]);


        // 2. Create Super Admin role if not exists
        $role = Role::Create(['name' => 'supper_admin']);

        // 3. Assign all permissions to this role
        $permissions = Permission::all();
        $role->givePermissionTo($permissions);

        // 4. Assign role to user
        $admin->assignRole($role);

        // Write installation date to the file
        file_put_contents(storage_path('installed'), 'Installed on: ' . now());

        // Run database seeder
        Artisan::call('db:seed');

        // Clear cache and config
        Artisan::call('config:cache');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');



        return response()->json(['status' => 'success']);
    }
}
