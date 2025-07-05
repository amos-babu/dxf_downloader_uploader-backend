<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Facades\Log;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ImageProcessing
{
    public function similarImages($image)
    {
        $files = File::all();
        $hasher = new ImageHash(new DifferenceHash());
        $imagePath = storage_path("app/public/{$image}");

        if (! file_exists($imagePath)) {
            dd('Image file does not exist at: '.$imagePath);
        }

        $targetHash = $hasher->hash($imagePath);

        $results = [];

        foreach($files as $file){
            $comparePath = storage_path("app/public/{$file->picture_path}");

            if (!file_exists($comparePath)) {
                dd("Missing File: ". $comparePath);
                // Log::warning("Missing File: ". $comparePath);
                continue;
            }
            try {
                $compareHash = $hasher->hash($comparePath);

                $distance = $hasher->distance($targetHash, $compareHash);

                $results[] = [
                    'files' => $file,
                    'distance' => $distance
                ];

            } catch (\Exception $e) {
                dd('Error: '.$e->getMessage());
                // Log::error("Hashing Error: ". $e->getMessage());
            }
        }

        usort($results, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        $sortedFiles = collect($results)->pluck('files');

        return $sortedFiles;
    }
}
