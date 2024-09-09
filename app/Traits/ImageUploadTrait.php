<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{
    public function singleImage($request, $imageName, $folderName)
    {
        if ($request->hasFile($imageName)) {
            $image = $request->file($imageName);
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/' . $folderName), $imageName);
            return $folderName . '/' . $imageName;
        }
        return null;
    }

    public function base64($request, $imageName, $folderName)
    {
        // if ($request->has('image')) {
        //     $image = $request->image;  // your base64 encoded image
        //     $image = str_replace('data:image/png;base64,', '', $image);
        //     $image = str_replace(' ', '+', $image);
        //     $imageName = Str::random(10) . '.png';
        //     $filePath = 'images/' . $folderName . '/' . $imageName;

        //     // Decode and save the base64 image to storage
        //     \File::put(public_path($filePath), base64_decode($image));

        //     // Return the file path or URL if needed
        //     return $filePath; // or Storage::url($filePath) for URL
        // }

        // return null;

        if ($request->has('image')) {
            $image = $request->image;  // your base64 encoded image

            if (strpos($image, 'data:image/png;base64,') === 0) {
                $image = str_replace('data:image/png;base64,', '', $image);
            }
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10) . '.png';
            $relativeFilePath = 'images/' . $folderName . '/' . $imageName;
            $fullPath = public_path($relativeFilePath);

            $decodedImage = base64_decode($image);
            if ($decodedImage === false) {
                return response()->json(['error' => 'Base64 decode failed'], 400);
            }

            if (!File::put($fullPath, $decodedImage)) {
                return response()->json(['error' => 'File save failed'], 500);
            }
            return $relativeFilePath;
        }

        return null;
    }


    public function multipleImage($image, $imageName, $folderName)
    {
        if ($image->isValid()) { // Check if the image is valid
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/' . $folderName), $imageName);
            return $folderName . '/' . $imageName;
        }
        return null;
    }

}
