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

        foreach ($files as $file) {

            $this->info("Processing: " . $file);

            $imagePath = storage_path('app/public/' . $file);

            if (!file_exists($imagePath)) {
                continue;
            }

            $img = $manager->read($imagePath)
                ->scale(width: 800)
                ->toWebp(70);

            // Ganti extension jadi webp
            $newPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);

            Storage::disk('public')->put($newPath, $img);

            // Hapus file lama
            Storage::disk('public')->delete($file);
        }

        $this->info('All images compressed successfully!');
    }
}
