<?php

use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/tags/search', [TagController::class, 'search']);
Route::post('/generate-slug', [PostController::class, 'slugGenerator'])->name('generate-slug');




Route::get('/list', [MediaItemController::class, 'list'])->name('media.list');
Route::get('/latest', [MediaItemController::class, 'latest'])->name('media.latest');
Route::get('/media', [MediaItemController::class, 'indexAPI'])->name('api.media');
