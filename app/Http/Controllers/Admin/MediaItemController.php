<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MediaItemController extends Controller
{

    public function indexAPI(Request $request)
    {
        $query = MediaItem::query()->with('media');

        if ($request->has('search') && $search = $request->input('search')) {
            $query->where('title', 'like', "%$search%");
        }

        $media = $query->latest()->paginate(
            $request->input('per_page', 10),
            ['*'],
            'page',
            $request->input('page', 1)
        );

        return response()->json([
            'data' => $media->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'media' => $item->getAllMediaAttributes(),
                ];
            }),
            'current_page' => $media->currentPage(),
            'last_page' => $media->lastPage(),
        ]);
    }


    public function index()
    {
        return Inertia::render('admin/media/Index');
    }

    public function create()
    {
        return Inertia::render('admin/media/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,webp,mp4|max:20480',
        ]);

        $mediaItem = MediaItem::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        [$width, $height] = @getimagesize($request->file->getPathname());

        $mediaItem->addMediaFromRequest('file')
            ->withCustomProperties(['width' => $width, 'height' => $height])
            ->toMediaCollection('default');

        return back();
    }

    public function destroy(MediaItem $mediaItem)
    {
        $mediaItem->delete();
        return back();
    }

    public function json(Request $request)
    {
        $query = MediaItem::query();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', "%$search%");
        }

        $media = $query->with('media')->latest()->get();

        return response()->json($media->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'media' => $item->getFirstMediaAttributes(),
        ]));
    }

    // ruta: GET /admin/media-manager/list
    public function list()
    {
        $media = MediaItem::with('media')->latest()->get();


        if(!$media) {
            return response()->json(null);
        }

        return response()->json($media->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'description' => $item->description,
            'media' => $item->getAllMediaAttributes(),
        ]));
    }

//    zadnji fajl koji se poziva nakon upload
    public function latest()
    {
        $mediaItem = MediaItem::with('media')->latest()->first();

        if(!$mediaItem) {
            return response()->json(null);
        }

        return response()->json([
            'id' => $mediaItem->id,
            'title' => $mediaItem->title,
            'description' => $mediaItem->description,
            'media' => $mediaItem->getAllMediaAttributes(),
        ]);
    }



}
