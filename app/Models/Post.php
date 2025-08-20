<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;



class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'slug',
        'status',
        'published_at',
        'featured',
        'author_id',
        'category_id',
        'video',
        'searchable_text',
        'version',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'featured' => 'boolean',
        'video' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function mediaItems()
    {
        return $this->belongsToMany(MediaItem::class, 'media_item_post')->withTimestamps();
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = json_encode($value);
    }

    public function getContentAttribute($value)
    {
        return json_decode($value, true);
    }
}
