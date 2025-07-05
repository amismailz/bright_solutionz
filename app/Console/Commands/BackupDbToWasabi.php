<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupDbToWasabi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db-wasabi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup only the database to Wasabi storage';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $fileName = "db_backup_$timestamp.sql";
        $localPath = storage_path("app/backups/$fileName");

        File::ensureDirectoryExists(dirname($localPath));

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');

        // Build mysqldump command
        if (!empty($dbPass)) {
            $dumpCommand = "mysqldump -h $dbHost -u $dbUser -p'$dbPass' $dbName > $localPath";
        } else {
            $dumpCommand = "mysqldump -h $dbHost -u $dbUser $dbName > $localPath";
        }

        exec($dumpCommand, $output, $returnCode);

        if ($returnCode !== 0 || !file_exists($localPath)) {
            $this->error("❌ Failed to create DB dump.");
            return Command::FAILURE;
        }

        $this->info("✔ Database dump created at: $localPath");

        try {
            $disk = Storage::disk('wasabi');
            $uploaded = $disk->put("backups/$fileName", file_get_contents($localPath));

            if ($uploaded) {
                $this->info("✅ Database backup uploaded to Wasabi.");
            } else {
                $this->error("❌ Failed to upload to Wasabi.");
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
        }

        File::delete($localPath);
        $this->info("🧹 Local SQL file cleaned up.");
    }
}
