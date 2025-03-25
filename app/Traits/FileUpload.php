<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait FileUpload
{
    public function uploadFile($request, $resizeImg = true)
    {
        $allowedMimeTypes = ["image/jpeg", "image/gif", "image/png"];
        $contentType = $request->file("attachment")->getClientMimeType();

        $photo = null;
        if (!in_array($contentType, $allowedMimeTypes)) {
            $path = Storage::disk("azure")->putFile(
                "main",
                $request->file("attachment"),
                "public"
            );
            return env(
                "AZURE_STORAGE_URL",
                "https://greep.blob.core.windows.net"
            ) .
                "/" .
                $path;
        } else {
            $file = $request->file("attachment");
            $extension = $file->getClientOriginalExtension() ?: "jpg";
            $fileName = Str::random(30) . "." . $extension;

            // Create a temporary file to store the optimized image
            $tempPath = storage_path("app/temp_img/" . $fileName);

            // Save the image to the temp folder
            $file->move(storage_path("app/temp_img/"), $fileName);

            // Optimize the image
            $optimizer = app(OptimizerChain::class);

            $optimizer->optimize($tempPath);

            // If resizing is enabled, resize the optimized image
            if ($resizeImg) {
                $image = Image::make($tempPath)->encode("jpeg", 60);
                $image = $image->stream();
                $imageContent = $image->__toString();
            } else {
                $imageContent = file_get_contents($tempPath);
            }

            // Upload the optimized (and possibly resized) image to Azure
            Storage::disk("azure")->put(
                "main/" . $fileName,
                $imageContent,
                "public"
            );

            // Delete the temporary file
            unlink($tempPath);

            return env(
                "AZURE_STORAGE_URL",
                "https://greep.blob.core.windows.net"
            ) .
                "/" .
                "main/" .
                $fileName;
        }
    }
}
