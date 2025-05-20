<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
/**
 * image intervention v3
 */
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

trait ImageSaveTrait
{
    private function saveImage($file_destination, $image_attribute, $width = NULL, $height = NULL): string
    {
        /**
         * Check and create directory if not exists
         */
        if (!File::isDirectory(public_path('uploads/' . $file_destination))) {
            File::makeDirectory(public_path('uploads/' . $file_destination), 0777, true, true);
        }

        /**
         * SVG conditions
         */
        if ($image_attribute->extension() == 'svg') {
            $file_name = time() . Str::random(10) . '.' . $image_attribute->extension();
            $path = 'uploads/' . $file_destination . '/' . $file_name;
            $image_attribute->move(public_path('uploads/' . $file_destination . '/'), $file_name);
            return $path;
        }


        /**
         * make the image accessible in image intervention
         */
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($image_attribute);

        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            /**
             * resize the image and save it
             */
            $image->scale($width, $height);
            $image->pad($width, $height, 'fff');

            $encoded = $image->encode(new WebpEncoder);
            $return_path = 'uploads/' . $file_destination . '/' . time().'-'. Str::random(10) . '.webp';
            $encoded->save(public_path($return_path));

            return $return_path;
        }
        else
        {
            /**
             * just save it (not recommended)
             */
            $encoded = $image->encode(new WebpEncoder);
            $return_path = 'uploads/' . $file_destination . '/' . time().'-'. Str::random(10) . '.webp';
            $encoded->save(public_path($return_path));
            return $return_path;
        }
    }

    private function updateImage($image_old_attribute, $file_destination, $image_new_attribute, $width = NULL, $height = NULL): string
    {
        /**
         * Check and create directory if not exists
         */
        if (!File::isDirectory(public_path('uploads/' . $file_destination))) {
            File::makeDirectory(public_path('uploads/' . $file_destination), 0777, true, true);
        }

        /**
         * SVG conditions
         */
        if ($image_new_attribute->extension() == 'svg') {
            $file_name = time() . Str::random(10) . '.' . $image_new_attribute->extension();
            $path = 'uploads/' . $file_destination . '/' . $file_name;
            $image_new_attribute->move(public_path('uploads/' . $file_destination . '/'), $file_name);
            return $path;
        }


        /**
         * make the image accessible in image intervention
         */
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($image_new_attribute);

        if ($width != null && $height != null && is_int($width) && is_int($height)) {
            /**
             * resize the image and save it
             */
            $image->scale($width, $height);
            $image->pad($width, $height, 'fff');

            $encoded = $image->encode(new WebpEncoder);
            $return_path = 'uploads/' . $file_destination . '/' . time().'-'. Str::random(10) . '.webp';
            $encoded->save(public_path($return_path));

            File::delete($image_old_attribute);
            return $return_path;
        }
        else
        {
            /**
             * just save it (not recommended)
             */
            $encoded = $image->encode(new WebpEncoder);
            $return_path = 'uploads/' . $file_destination . '/' . time().'-'. Str::random(10) . '.webp';
            $encoded->save(public_path($return_path));

            File::delete($image_old_attribute);
            return $return_path;
        }
    }

    private function deleteImage($path)
    {
        if ($path == null || $path == '') {
            return null;
        }

        try {
            File::delete($path);
        } catch (\Exception $e) {
            //
        }

        File::delete($path);
    }
}
