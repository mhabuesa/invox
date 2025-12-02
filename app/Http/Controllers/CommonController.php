<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;


class CommonController extends Controller
{

    // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'dbBackup'   => 'db_backup',
            'activityLog' => 'activity_log',
        ]);
    }

    public function activityLog()
    {
        $logs = UserLog::latest()->get();
        return view('activityLog.index', [
            'logs' => $logs,
        ]);
    }

    public function activityLog_delete($id)
    {
        if(Auth::user()->email == 'demo@invox.com'){
            return redirect()->back()->with('error', 'Demo user can not perform this action.');
        }
        if ($id == '1') {
            UserLog::where('created_at', '<', now()->subDays(7))->delete();
        } elseif ($id == '2') {
            UserLog::where('created_at', '<', now()->subDays(15))->delete();
        } elseif ($id == '3') {
            UserLog::where('created_at', '<', now()->subDays(30))->delete();
        } elseif ($id == '4') {
            UserLog::truncate();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ID'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Activity logs deleted successfully.'
        ], 200);
    }

    public function dbBackup()
    {
        if(Auth::user()->email == 'demo@invox.com'){
            return redirect()->back()->with('error', 'Demo user can not perform this action.');
        }

        Artisan::call('backup:database');

        $backupPath = storage_path('app/backups');
        $files = glob($backupPath . '/*.sql');



        if (!$files) {
            return response()->json(['message' => 'No backup file found.'], 404);
        }

        $latestFile = collect($files)->sortByDesc(function ($file) {
            return filemtime($file);
        })->first();

        // Log the action
        userLog('DB Backup', 'Downloaded a Database Backup');

        return response()->download($latestFile);
    }
}
