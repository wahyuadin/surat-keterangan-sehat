<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CompressImages extends Command
{
    protected $signature = 'images:compress';
    protected $description = 'Compress existing images in storage';

    public function handle()
    {
        $manager = new ImageManager(new Driver());

        $files = Storage::disk('public')->files('registration');

        if (empty($files)) {
            $this->info('No files found.');
            return;
        }

        foreach ($files as $file) {

            // Skip jika sudah webp
            if (preg_match('/\.webp$/i', $file)) {
                $this->line("Skipped (already webp): " . $file);
                continue;
            }

            // Hanya proses jpg/jpeg/png
            if (!preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                continue;
            }

            $path = storage_path('app/public/' . $file);

            if (!file_exists($path)) {
                $this->warn("Skipped (file not found): " . $file);
                continue;
            }

            if (filesize($path) == 0) {
                $this->warn("Skipped (empty file): " . $file);
                continue;
            }

            // Skip jika file sudah kecil (<100KB)
            if (filesize($path) < 100000) {
                $this->line("Skipped (already small): " . $file);
                continue;
            }

            try {

                $this->info("Processing: " . $file);

                $image = $manager->read($path)
                    ->scale(width: 800)
                    ->toWebp(70);

                $newPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);

                Storage::disk('public')->put($newPath, $image);

                Storage::disk('public')->delete($file);

                $this->info("Compressed → " . $newPath);
            } catch (\Throwable $e) {

                $this->error("Failed: " . $file);
                $this->error("Reason: " . $e->getMessage());
                continue;
            }
        }

        $this->info('Compression process completed.');
    }
}
