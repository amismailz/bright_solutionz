<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BackupToWasabi extends Command
{


    protected $signature = 'backup:wasabi';

    protected $description = 'Backup DB and media to Wasabi storage';

    public function handle()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $localPath = storage_path("app/backups/backup_$timestamp");
        $zipPath = "$localPath.zip";

        File::makeDirectory($localPath, 0755, true);


        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST');

        $sqlPath = "$localPath/db.sql";
        if (!empty($dbPass)) {
            $dumpCommand = "mysqldump -h $dbHost -u $dbUser -p'$dbPass' $dbName > $sqlPath";
        } else {
            $dumpCommand = "mysqldump -h $dbHost -u $dbUser $dbName > $sqlPath";
        }
        exec($dumpCommand);


        File::copyDirectory(storage_path('app/public'), "$localPath/storage/public");
        File::copyDirectory(storage_path('app/private'), "$localPath/storage/private");


        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($localPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($localPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            $this->info("✔ ZIP created at: $zipPath");
        } else {
            $this->error("❌ Could not create ZIP file.");
            return 1;
        }


        if (!file_exists($zipPath)) {
            $this->error("❌ ZIP file not found at: $zipPath");
            return Command::FAILURE;
        }

        try {
            $disk = Storage::disk('wasabi');
            $success = $disk->put("backups/backup_$timestamp.zip", file_get_contents($zipPath));
            $this->error("$success");

            if ($success) {
                $this->info("✅ Successfully uploaded to Wasabi.");
            } else {
                $this->error("❌ Upload to Wasabi failed.");
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception during upload: " . $e->getMessage());
        }



        File::deleteDirectory($localPath);
        File::delete($zipPath);

        $this->info("Backup completed and uploaded to Wasabi.");
    }
}
