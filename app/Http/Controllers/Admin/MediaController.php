<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,gif|max:10240', // Limit fajla na 10MB
        ]);

        $file = $request->file('file');
        $path = $file->store('public/media'); // Smesti sliku u `storage/app/public/media`



        // Spremi podatke o slici u tabelu Media (ako koristiš Spatie Media Library, koristi metodu `addMedia()`)
        $media = Media::create([
            'file_name' => basename($path),
            'collection_name' => 'default',
            'disk' => 'public',
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json($media, 201);
    }
}
