<?php

namespace App\Models;

use App\Traits\HasMediaAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaItem extends Model implements HasMedia
{
    use InteractsWithMedia, HasMediaAttributes;
    protected $fillable = ['title', 'description'];

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'media_item_post')->withTimestamps();
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        $this
            ->addMediaConversion('post')
            ->fit(Fit::Contain, 1000, 1000)
            ->nonQueued();
    }
}
