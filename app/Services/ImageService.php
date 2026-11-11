<?php

namespace App\Services;

use Illuminate\Support\Str;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function save_image($img)
    {
        $path = Str::uuid() . "." . $img->guessExtension();

        $img->move(storage_path("app/public"), $path);
        return "/storage/" . $path;
    }

    public function remove_image(?string $path): void
    {
        if (! $path) {
            return;
        }

        $relative = Str::after($path, '/storage/');

        $fullPath = storage_path('app/public/' . $relative);

        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
