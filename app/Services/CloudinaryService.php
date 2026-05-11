<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Transformation\Resize;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => config('cloudinary.secure', true),
            ],
        ]);
    }

    /**
     * Upload an image to Cloudinary.
     */
    public function upload(UploadedFile $file, string $folder = 'products', array $options = []): ?array
    {
        try {
            $uploadOptions = array_merge([
                'folder' => "the_vault/{$folder}",
                'resource_type' => 'image',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ],
            ], $options);

            $uploadedFile = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                $uploadOptions
            );

            return [
                'public_id' => $uploadedFile['public_id'],
                'url' => $uploadedFile['secure_url'],
                'width' => $uploadedFile['width'] ?? null,
                'height' => $uploadedFile['height'] ?? null,
                'format' => $uploadedFile['format'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple images to Cloudinary.
     */
    public function uploadMultiple(array $files, string $folder = 'products'): array
    {
        $results = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $result = $this->upload($file, $folder);
                if ($result) {
                    $results[] = $result;
                }
            }
        }
        return $results;
    }

    /**
     * Delete an image from Cloudinary.
     */
    public function delete(string $publicId): bool
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId);
            return ($result['result'] ?? '') === 'ok';
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a public ID from a Cloudinary URL.
     */
    public function getPublicIdFromUrl(string $url): ?string
    {
        $parts = explode('/', $url);
        $filename = end($parts);
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Extract the public ID from the URL path
        if (preg_match('#/v\d+/(.+?)\.#', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}