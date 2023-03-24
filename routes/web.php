<?php

use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PoemsController;
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

Route::get('/dash', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/poems', function () {
    return Inertia::render('Admin/Poems');
})->middleware(['auth', 'verified'])->name('poems');

//admin
Route::middleware('auth')->group(function () {

    //users
    Route::get('dash/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('dash/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('dash/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('dash/users/edit/{user}', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('dash/users/{user}', [UsersController::class, 'update'])->name('users.update');

    //poems
    Route::get('dash/poems', [PoemsController::class, 'index'])->name('poems.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
