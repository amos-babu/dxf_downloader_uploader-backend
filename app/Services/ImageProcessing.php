<?php

namespace App\Services;

use App\Models\File;
use Jenssegers\ImageHash\ImageHash;
use Jenssegers\ImageHash\Implementations\DifferenceHash;

class ImageProcessing
{
    public function similarImages($image)
    {
        $files = File::all();
        $hasher = new ImageHash(new DifferenceHash);
        $imagePath = storage_path($image);

        if (! file_exists($imagePath)) {
            dd('Image file does not exist at: '.$imagePath);
        }

        try {
            $hash = $hasher->hash($imagePath);
            echo $hash;
        } catch (\Exception $e) {
            dd('Error: '.$e->getMessage());
        }

        // foreach($files as $file){

        // }
    }
}
