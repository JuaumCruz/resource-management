<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/resources', function () {
        return Inertia::render('Resources/Index');
    })->name('resources.index');

    Route::get('/resources/create', function () {
        return Inertia::render('Resources/Create');
    })->name('resources.create');

    Route::get('/resources/{resource}/edit', function (App\Models\Resource $resource) {
        return Inertia::render('Resources/Edit', [
            'resource' => $resource->load(['category', 'tags'])
        ]);
    })->name('resources.edit');

    Route::get('/categories', function () {
        return Inertia::render('Categories/Index');
    })->name('categories.index');

    Route::get('/categories/create', function () {
        return Inertia::render('Categories/Create');
    })->name('categories.create');

    Route::get('/categories/{category}/edit', function (App\Models\Category $category) {
        return Inertia::render('Categories/Edit', [
            'category' => $category->loadCount('resources')
        ]);
    })->name('categories.edit');

    Route::get('/tags', function () {
        return Inertia::render('Tags/Index');
    })->name('tags.index');

    Route::get('/tags/create', function () {
        return Inertia::render('Tags/Create');
    })->name('tags.create');

    Route::get('/tags/{tag}/edit', function (App\Models\Tag $tag) {
        return Inertia::render('Tags/Edit', [
            'tag' => $tag->loadCount('resources')
        ]);
    })->name('tags.edit');
});

require __DIR__.'/auth.php';
