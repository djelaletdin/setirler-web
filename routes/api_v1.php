<?php

use App\Http\Controllers\api\v1\HomePageController;
use App\Http\Controllers\api\v1\PoemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', HomePageController::class);

Route::get('/poems/{poem}', [PoemController::class, 'show'])->name('api.poems.show');
