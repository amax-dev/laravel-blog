<?php

use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Fronted\HomeController;
use Illuminate\Support\Facades\Route;


// Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');
});

// admin
Route::prefix('admin')
    ->middleware(['auth', 'verified']) // možeš dodati i role provjeru
    ->name('admin.')
    ->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Dodajemo i rute za postove, kategorije itd.
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);

        // Media Manager
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaItemController::class, 'index'])->name('index');
            Route::post('/', [MediaItemController::class, 'index'])->name('index.search');

            Route::get('/create', [MediaItemController::class, 'create'])->name('create');

            Route::post('/upload', [MediaItemController::class, 'store'])->name('store');
            Route::delete('/{mediaItem}', [MediaItemController::class, 'destroy'])->name('destroy');

//            Route::get('/media-items-json', [MediaItemController::class, 'json'])->name('json');


        });
    });




require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
