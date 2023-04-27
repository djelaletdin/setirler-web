<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PoemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;

use App\Http\Controllers\Admin\PoemController as AdminPoemController;


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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index')->name('users.index');
    Route::get('/@{user:username}', 'show')->name('users.show');
});


Route::controller(PoemController::class)->group(function () {
    Route::get('/poems/{poem:slug}', 'show')->name('poems.show');
});


Route::controller(CommentController::class)->middleware('auth')->group(function () {
    Route::post('/poems/{poem}/comments', 'store')->name('comments.store');
    Route::post('/comments/{comment}/votes','vote');
    Route::delete('/comments/{comment}', 'destroy')->name('comments.destroy');
});


Route::get('/tags/{tag:slug}', [TagController::class, 'show'])->name('tags.show');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
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
    Route::get('/admin', [AdminPoemController::class, 'index'])->name('profile.edit');
});


require __DIR__.'/auth.php';
