<?php

namespace App\Console\Commands;

use App\Jobs\SendBackupMail;
use Illuminate\Console\Command;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use function Illuminate\Log\log;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';
    protected $description = 'Backup the database and store it in storage/app/backups';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $this->info("Starting database backup...");

        $database = config('database.connections.mysql.database');
        $backupName = $database . '_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $backupFolder = 'backups';
        $backupPath = $backupFolder . '/' . $backupName;

        // Create backup folder if not exists
        if (!Storage::exists($backupFolder)) {
            Storage::makeDirectory($backupFolder);
        }

        // Delete old backups
        foreach (Storage::files($backupFolder) as $file) {
            Storage::delete($file);
            $this->info("Old backup deleted: $file");
        }

        // Generate SQL dump
        $sqlDump = '';
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];

            // Table drop statement
            $sqlDump .= "DROP TABLE IF EXISTS `$tableName`;\n";

            // Table structure
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
            $sqlDump .= $createTable . ";\n\n";

            // Table data
            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $rowData = array_map(fn($value) => addslashes($value), (array)$row);
                $sqlDump .= "INSERT INTO `$tableName` VALUES ('" . implode("','", $rowData) . "');\n";
            }

            $sqlDump .= "\n\n";
        }

        // Save SQL file locally
        Storage::disk('local')->put($backupPath, $sqlDump);
        $fullPath = storage_path('app/' . $backupPath);
        $this->info("Backup created: $fullPath");

    }
}
