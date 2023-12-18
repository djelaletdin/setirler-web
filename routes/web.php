<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LikeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;

use App\Http\Controllers\Admin\PoemController as AdminPoemController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|
|--------------------------------------------------------------------------
| Client Side
|--------------------------------------------------------------------------
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

Route::controller(DashboardController::class)->group(function () {
    Route::get('/', 'index')->name('dashboard');
});


Route::controller(UserController::class)->group(function () {
    Route::get('/users/{category?}', 'index')->name('users.index');
    Route::get('/@{user:username}', 'show')->name('users.show');
});


Route::controller(PoemController::class)->name('poems.')->group(function () {
    Route::get('/poems/create', 'create')->name('create');
    Route::get('/poems/{poem:slug}', 'show')->name('show');
    Route::post('/poems', 'store')->name('store');
});


Route::controller(CommentController::class)->middleware('auth')->group(function () {
    Route::post('/poems/{poem}/comments', 'store')->name('comments.store');
    Route::post('/comments/{comment}/votes','vote');
    Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
});

Route::controller(LikeController::class)->middleware('auth')->group(function () {
    Route::post('/poems/{poem}/like', 'store')->name('like.store');
});

Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Side
|--------------------------------------------------------------------------
|
*/

Route::middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/admin/poems', [AdminPoemController::class, 'index'])->name('poems.index');
    Route::get('/admin/poems/{poem:slug}/edit', [AdminPoemController::class, 'edit'])->name('poems.edit');
    Route::get('/admin/poems/unpublished', [AdminPoemController::class, 'unpublished'])->name('poems.unpublished');
    Route::put('/admin/poems/{poem}', [AdminPoemController::class, 'update'])->name('poems.update');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/{user:username}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/user/{user}', [AdminUserController::class, 'update'])->name('users.update');

    // ...
});


require __DIR__.'/auth.php';
