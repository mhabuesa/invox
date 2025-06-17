<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


class CommonController extends Controller
{

    // Permissions Method
    public function __construct()
    {
        $this->setPermissions([
            'dbBackup'   => 'db_backup',
            'activityLog'=> 'activity_log',
        ]);
    }
    public function dbBackup()
    {

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

    public function activityLog(){
        $logs = UserLog::latest()->get();
        return view('activityLog.index', [
            'logs' => $logs,
        ]);
    }
}
