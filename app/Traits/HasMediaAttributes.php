<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
trait HasMediaAttributes
{
    public function getFirstMediaAttributes(string $collection = 'default'): ?array
    {
        $media = $this->getFirstMedia($collection);

        if (! $media instanceof Media) {
            return null;
        }

        return [
            'id' => $media->id,
            'url' => $media->getUrl(),
            'full_url' => $media->getFullUrl(),
            'path' => $media->getPath(),
            'mime_type' => $media->mime_type,
            'extension' => $media->extension,
            'size' => $media->size,
            'human_size' => $this->formatBytes($media->size),
            'date' => $media->created_at->format('d.m.Y'),
            'name' => $media->name,
            'file_name' => $media->file_name,
            'collection_name' => $media->collection_name,
            'custom_properties' => $media->custom_properties,
            'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : null,
            'post_img_url' => $media->hasGeneratedConversion('post') ? $media->getUrl('post') : null,
            'responsive_images' => $media->getResponsiveImageUrls(),
            'width' => $media->getCustomProperty('width'),
            'height' => $media->getCustomProperty('height'),
        ];
    }


    public function getAllMediaAttributes(string $collection = 'default'): array
    {
        $mediaCollection = $this->getMedia($collection);

        return $mediaCollection->map(function (Media $media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'full_url' => $media->getFullUrl(),
                'mime_type' => $media->mime_type,
                'path' => $media->getPath(),
                'extension' => $media->extension,
                'size' => $media->size,
                'human_size' => $this->formatBytes($media->size),
                'date' => $media->created_at->toDateTimeString(),
                'name' => $media->name,
                'file_name' => $media->file_name,
                'collection_name' => $media->collection_name,
                'custom_properties' => $media->custom_properties,
                'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : null,
                'post_img_url' => $media->hasGeneratedConversion('post') ? $media->getUrl('post') : null,
                'responsive_images' => $media->getResponsiveImageUrls(),
                'width' => $media->getCustomProperty('width'),
                'height' => $media->getCustomProperty('height'),
            ];
        })->toArray();
    }

    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $power = (int) floor(($bytes ? log($bytes) : 0) / log(1024));
        $power = min($power, count($units) - 1);

        $bytes /= (1 << (10 * $power));

        return round($bytes, $precision) . ' ' . $units[$power];
    }
}
