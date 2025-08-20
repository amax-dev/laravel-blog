<?php

namespace App\Http\Controllers\Fronted;

use App\Http\Controllers\Controller;
use App\Models\Post;


class HomeController extends Controller
{
    public function index()
    {

        // Postovi
        $latestPosts = Post::query()
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->with([
                // Ograniči kolone u relacijama radi perf.
                'author:id,name',
                'category:id,name,slug',
                'mediaItems.media' // loaduj sve medije za prilagodbu
            ])
            ->get(['id', 'title', 'slug', 'author_id', 'category_id', 'featured', 'published_at']);

        $featuredPost = $latestPosts->firstWhere('featured', 1);

        if (!$featuredPost) {
            $featuredPost = Post::query()
                ->where('featured', 1)
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->with([
                    'author:id,name',
                    'category:id,name,slug',
                    'mediaItems.media'
                ])
                ->first(['id', 'title', 'slug', 'author_id', 'category_id', 'featured', 'published_at']);
        }

        $otherPosts = $latestPosts;
        if ($featuredPost) {
            $otherPosts = $latestPosts->reject(fn($post) => $post->id === $featuredPost->id);
        }





        return inertia('fronted/home/Index', [
            'featured_post' => $featuredPost ? $this->formatPost($featuredPost, true) : null,
            'other_posts'   => $otherPosts->map(fn($post) => $this->formatPost($post))->values(),
        ]);
    }

    function formatMedia($post, $variant = 'thumb')
    {
        if ($post->mediaItems->isNotEmpty()) {
            $media = $post->mediaItems->first()->media->first(); // Prvi povezani Media
            if ($media) {
                // Ovo su direktni pristupi poljima, bez getAllMediaAttributes
                return [
                    'id'    => $media->id,
                    'alt'   => $media->name,
                    'url'   => $variant === 'thumb'
                        ? ($media->getUrl('thumb') ?? $media->getUrl())
                        : ($media->getUrl('post') ?? $media->getUrl()),
                    'mime'  => $media->mime_type,
                    'size'  => $media->human_readable_size ?? null,
                    'width' => $media->custom_properties['width'] ?? null,
                    'height'=> $media->custom_properties['height'] ?? null,
                ];
            }
        }

        return null;
    }



    function formatPost($post, $isFeatured = false)
    {
        return [
            'id'        => $post->id,
            'title'     => $post->title,
            'slug'      => $post->slug,
            'published_at' => $post->published_at->locale('bs')->diffForHumans(),
            'featured'  => $post->featured,
            'author'    => $post->author ? [
                'id'   => $post->author->id,
                'name' => $post->author->name
            ] : null,
            'category'  => $post->category ? [
                'id'   => $post->category->id,
                'name' => $post->category->name,
                'slug' => $post->category->slug
            ] : null,
            // Samo featured ima sliku velikog formata, ostali thumb:
            'image'     => $this->formatMedia($post, $isFeatured ? 'post' : 'thumb'),
        ];
    }



}
