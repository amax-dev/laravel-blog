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
            ->with(['author', 'category', 'tags'])
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

        return redirect()->route('admin.posts.index');

    }

    public function edit(Post $post)
    {
        $post->load(['tags', 'media']);

        return Inertia::render('admin/posts/Edit', [
            'post' => $post,
            'categories' => Category::query()->select('id', 'name')->get(),
            'users' => User::query()->select('id', 'name')->get(),
            'tags' => Tag::query()->select('id', 'name')->get(),
        ]);
    }

    public function update(PostRequest $request, Post $post)
    {
        DB::transaction(function () use ($request, $post) {
            $post->update([
                'title' => $request->title,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'slug' => $request->slug ?: Str::slug($request->title['bs']),
                'status' => $request->status,
                'published_at' => $request->published_at,
                'featured' => $request->featured ?? false,
                'author_id' => $request->author_id,
                'category_id' => $request->category_id,
                'version' => $post->version + 1,
            ]);

            $post->tags()->sync($request->tags);
        });

        return redirect()->route('admin.posts.index')->with('success', 'Post je uspješno ažuriran.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post je uspješno obrisan.');
    }
}
