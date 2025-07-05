<?php

namespace App\Console\Commands;

use App\Models\Point;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Maestroerror\HeicToJpg;

class ConvertHeicImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:convert-heic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert HEIC/HEIF images to JPG for all Point records';

    public function handle(): void
    {
        $this->info('Starting HEIC to JPG conversion...');

        Point::with('media')->chunk(100, function ($points) {
            foreach ($points as $point) {
                foreach ($point->getMedia('images') as $media) {
                    $path = $media->getPath();
                    $extension = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));

                    if (in_array($extension, ['heic', 'heif'])) {
                        try {
                            $tempPath = storage_path('app/temp');
                            if (!file_exists($tempPath)) {
                                mkdir($tempPath, 0755, true);
                            }

                            $filename = uniqid() . '.jpg';
                            $outputPath = $tempPath . '/' . $filename;

                            HeicToJpg::convert($path)->saveAs($outputPath);

                            $point->addMedia($outputPath)
                                ->preservingOriginal()
                                ->toMediaCollection('images');

                            $media->delete();
                            unlink($outputPath);

                            $this->info("Converted HEIC image for point ID {$point->id}");
                        } catch (\Exception $e) {
                            Log::error("Failed converting HEIC for point ID {$point->id}: " . $e->getMessage());
                            $this->error("Error on point ID {$point->id}");
                        }
                    }
                }
            }
        });

        $this->info('Conversion process completed.');
    }
}
