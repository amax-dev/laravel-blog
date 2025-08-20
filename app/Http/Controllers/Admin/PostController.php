<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;


class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with(['author:id,name', 'category:id,name', 'tags:id,name'])
            ->when($request->search, function ($query, $search) {
                $query->where('searchable_text', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('admin/posts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['search'])
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/posts/Create', [
            'categories' => Category::query()->select('id', 'name', 'slug')->get(),
        ]);
    }

    public function slugGenerator(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $i = 1;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        return response()->json(['slug' => $slug]);
    }

    public function store(PostRequest $request)
    {

        $validated = $request->validated();



        $validated['author_id'] = auth()->user()->id;

        $tagIds = collect($request->input('tags', []))->map(function ($tag) {
            if (isset($tag['id'])) {
                return $tag['id'];
            }
            return Tag::query()->firstOrCreate(
                ['name' => $tag['name']],
                ['slug' => Str::slug($tag['name'])]
            )->id;
        })->all();

        $post = Post::query()->create($validated);

        $post->tags()->sync($tagIds);
        $post->mediaItems()->sync($validated['featured_image_id']);

        return redirect()->route('admin.posts.edit',$post->id);

    }

    public function edit(Post $post)
    {
        $post->load(['tags', 'mediaItems.media']); // eager load podrelaciju media

        $featuredImage = null;

        // Pronađi prvi MediaItem koji ima povezane medije
        $featuredMediaItem = $post->mediaItems->first(function ($mediaItem) {
            return $mediaItem->media->isNotEmpty();
        });

        if ($featuredMediaItem) {
            // Prva media za taj MediaItem
            $media = $featuredMediaItem->media->first();

            if ($media) {
                $featuredImage = [
                    'id' => $media->id,
                    'url' => $media->getFullUrl(),
                    'thumb_url' => $media->getFullUrl('thumb') ?? null,
                    'post_img_url' => $media->getFullUrl('post') ?? null,
                    'mime_type' => $media->mime_type,
                    'extension' => $media->extension,
                    'size' => $media->size,
                    'human_size' => $media->human_readable_size,
                    'file_name' => $media->file_name,
                    'collection_name' => $media->collection_name,
                ];
            }
        }

        $post->setAttribute('featured_image', $featuredImage);

        return Inertia::render('admin/posts/Edit', [
            'post' => $post,
            'categories' => Category::query()->select('id', 'name')->get(),
            'users' => User::query()->select('id', 'name')->get(),
            'tags' => Tag::query()->select('id', 'name')->get(),
        ]);
    }




    public function update(PostRequest $request, Post $post)
    {
        if (is_string($request->content)) {
            $decoded = json_decode($request->content, true);
            if ($decoded !== null && isset($decoded['content'])) {
                // zamenjuj content sa unutrašnjim content poljem iz objekta
                $request->merge(['content' => $decoded['content']]);
            }
        }

        $validated = $request->validated();


        $post->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'slug' => $validated['slug'] ?: Str::slug($validated['title']),
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $validated['featured'] ?? false,
            'author_id' => auth()->user()->id,
            'category_id' => $validated['category_id'] ?? null,
            'version' => $post->version + 1,
        ]);


        return Inertia::render(route('admin.posts.edit', $post->id), [
            'post' => $post,
            'categories' => Category::select('id', 'name')->get(),
            'users' => User::select('id', 'name')->get(),
            'tags' => Tag::select('id', 'name')->get(),
            'success' => 'Post je uspješno ažuriran.',
        ]);
    }






    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post je uspješno obrisan.');
    }
}
