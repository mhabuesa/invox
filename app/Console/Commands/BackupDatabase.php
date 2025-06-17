<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $port = env('DB_PORT', 3306);
        $filename = 'backup-' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $command = "mysqldump --user={$user} --password=\"{$pass}\" --host={$host} --port={$port} {$db} > {$path}/{$filename}";

        $return = null;
        $output = null;
        exec($command, $output, $return);

        if ($return === 0) {
            $this->info("Database backup completed: {$filename}");
        } else {
            $this->error("Database backup failed.");
        }
    }
}
