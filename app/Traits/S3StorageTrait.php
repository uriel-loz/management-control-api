<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait S3StorageTrait
{
    public function uploadFile(
        string $binary_content,
        string $folder,
        string $file_name,
        string $visibility = 'private'
    ): string {
        $base_name = pathinfo($file_name, PATHINFO_FILENAME);
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $full_path = $extension
            ? "{$folder}/{$base_name}.{$extension}"
            : "{$folder}/{$base_name}";

        if ($this->fileExists($full_path)) {
            $counter = 1;

            do {
                $full_path = $extension
                    ? "{$folder}/{$base_name} ({$counter}).{$extension}"
                    : "{$folder}/{$base_name} ({$counter})";
                $counter++;
            } while ($this->fileExists($full_path));
        }

        Storage::disk('s3')->put($full_path, $binary_content, $visibility);

        return $full_path;
    }

    public function fileExists(string $path): bool
    {
        return Storage::disk('s3')->exists($path);
    }

    public function getFileUrl(string $path): string
    {
        return Storage::disk('s3')->url($path);
    }

    public function getPreSignedUrl(string $path): string
    {
        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(10));
    }

    public function deleteFile(string $path): bool
    {
        return Storage::disk('s3')->delete($path);
    }

    public function downloadFile(string $path): StreamedResponse
    {
        return Storage::disk('s3')->download($path);
    }
}
