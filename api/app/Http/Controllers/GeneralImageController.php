<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\GeneralImage;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Spatie\Image\Image;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Enums\ImageDriver;

class GeneralImageController extends Controller
{
    public function upload(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240']
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {

            $image = GeneralImage::create([
                'user_id' => $user->id,
                's3_path' => 'placeholder.jpg',
                's3_url' => 'placeholder.jpg',
                'filename' => $file->getClientOriginalName(),
            ]);

            $basePath = "images/general/{$image->id}";

            try {
                $originalPath = "{$basePath}/original.jpg";
                
                $this->convert_original_and_upload($file, $originalPath);

                $largePath = "{$basePath}/large.jpg";
                $thumbPath = "{$basePath}/thumb.jpg";
                
                $this->resize_and_upload($file, $largePath, 1024);
                $this->resize_and_upload($file, $thumbPath, 256);

                $s3Path = Storage::disk('s3')->path($originalPath);
                $s3Url = Storage::disk('s3')->url($s3Path);

                $image->update([
                    's3_path' => $s3Path,
                    's3_url' => $s3Url,
                ]);

                $uploadedImages[] = $image;
            } catch (Exception $e) {
                $image->delete();
                Log::debug($e->getMessage());
                return response()->json(['message' => 'Upload failed'], 500);
            }
        }

        return response()->json([
            'message' => 'Images uploaded successfully!',
            'images' => $uploadedImages,
        ]);
    }

    private function convert_original_and_upload($file, $path)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'img');

        Image::useImageDriver(ImageDriver::Gd)
            ->loadFile($file->getRealPath())
            ->format('jpg')
            ->save($tempFile);

        Storage::disk('s3')->put($path, file_get_contents($tempFile), 'public');

        unlink($tempFile);
    }

    private function resize_and_upload($file, $path, $width)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'img');

        Image::useImageDriver(ImageDriver::Gd)
            ->loadFile($file->getRealPath())
            ->format('jpg')
            ->fit(Fit::Max, $width)
            ->optimize()
            ->save($tempFile);

        Storage::disk('s3')->put($path, file_get_contents($tempFile), 'public');

        unlink($tempFile);
    }
}
