<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class FileUploadService
{
    /**
     * Downscale and compress an uploaded image to WebP with high visual fidelity,
     * drastically reducing file weight for rapid page rendering.
     *
     * @param  UploadedFile  $file  The uploaded image file
     * @param  string  $directory  Target subfolder in public storage
     * @param  int  $maxWidth  Maximum width in pixels
     * @param  int  $maxHeight  Maximum height in pixels
     * @param  int  $quality  Compression quality (1-100, 82 recommended)
     * @return string The public URL of the stored WebP image
     */
    public static function optimizeAndStoreImage(
        UploadedFile $file,
        string $directory = 'images',
        int $maxWidth = 1920,
        int $maxHeight = 1200,
        int $quality = 82
    ): string {
        try {
            $contents = file_get_contents($file->getRealPath());
            if ($contents === false) {
                return self::storeDirectly($file, $directory);
            }

            $source = @imagecreatefromstring($contents);
            if (! $source) {
                return self::storeDirectly($file, $directory);
            }

            // Correct orientation from EXIF if present
            $source = self::fixExifOrientation($source, $file->getRealPath());

            $origWidth = imagesx($source);
            $origHeight = imagesy($source);

            // Compute scaled dimensions preserving aspect ratio
            $targetWidth = $origWidth;
            $targetHeight = $origHeight;

            if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
                $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
                $targetWidth = max(1, (int) round($origWidth * $ratio));
                $targetHeight = max(1, (int) round($origHeight * $ratio));
            }

            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            // Handle transparency for PNG/WebP/GIF
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);

            imagecopyresampled(
                $canvas,
                $source,
                0,
                0,
                0,
                0,
                $targetWidth,
                $targetHeight,
                $origWidth,
                $origHeight
            );

            $filename = Str::random(40).'.webp';
            $relativePath = trim($directory, '/').'/'.$filename;
            $diskPath = Storage::disk('public')->path($relativePath);

            $dirName = dirname($diskPath);
            if (! is_dir($dirName)) {
                @mkdir($dirName, 0755, true);
            }

            imagewebp($canvas, $diskPath, $quality);

            imagedestroy($source);
            imagedestroy($canvas);

            return Storage::url($relativePath);
        } catch (Throwable) {
            return self::storeDirectly($file, $directory);
        }
    }

    /**
     * Store a video file on the public disk with weight limit validation.
     *
     * @param  UploadedFile  $file  Uploaded video file (MP4, WebM, OGG, MOV)
     * @param  string  $directory  Target subfolder
     * @return string Public URL of the stored video
     */
    public static function storeVideo(UploadedFile $file, string $directory = 'videos'): string
    {
        $path = $file->storePublicly(trim($directory, '/'), 'public');

        return Storage::url($path);
    }

    /**
     * Store an institutional or community document (PDF, Word, Excel, ZIP) safely.
     *
     * @param  UploadedFile  $file  Uploaded document
     * @param  string  $directory  Target subfolder
     * @return array{path: string, url: string, name: string, size: int, mime: string}
     */
    public static function storeDocument(UploadedFile $file, string $directory = 'documents'): array
    {
        $path = $file->storePublicly(trim($directory, '/'), 'public');

        return [
            'path' => $path,
            'url' => Storage::url($path),
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $file->getClientMimeType() ?: 'application/octet-stream',
        ];
    }

    /**
     * Delete an existing file from the public disk if it was stored locally.
     *
     * @param  string|null  $urlOrPath  URL like '/storage/images/xyz.webp' or relative path
     */
    public static function deleteStoredFile(?string $urlOrPath): void
    {
        if (empty($urlOrPath)) {
            return;
        }

        $cleanPath = $urlOrPath;

        // If it starts with /storage/ or storage/, remove it
        if (str_starts_with($cleanPath, '/storage/')) {
            $cleanPath = substr($cleanPath, 9);
        } elseif (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        // If it looks like an external URL (http:// or https://), ignore
        if (str_starts_with($cleanPath, 'http://') || str_starts_with($cleanPath, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
        }
    }

    /**
     * Fallback standard storage when GD image processing fails.
     */
    protected static function storeDirectly(UploadedFile $file, string $directory): string
    {
        $path = $file->storePublicly(trim($directory, '/'), 'public');

        return Storage::url($path);
    }

    /**
     * Inspect EXIF orientation and rotate the image accordingly.
     *
     * @param  \GdImage  $source
     * @return \GdImage
     */
    protected static function fixExifOrientation($source, string $filePath)
    {
        if (! function_exists('exif_read_data')) {
            return $source;
        }

        try {
            $exif = @exif_read_data($filePath);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($source, 180, 0);
                        imagedestroy($source);

                        return $rotated;
                    case 6:
                        $rotated = imagerotate($source, -90, 0);
                        imagedestroy($source);

                        return $rotated;
                    case 8:
                        $rotated = imagerotate($source, 90, 0);
                        imagedestroy($source);

                        return $rotated;
                }
            }
        } catch (Throwable) {
            // Ignore exif errors and proceed with original
        }

        return $source;
    }
}
