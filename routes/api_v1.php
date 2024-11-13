<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\HomePageController;
use App\Http\Controllers\api\v1\PoemController;
use App\Http\Controllers\api\v1\CommentController;
use App\Http\Controllers\api\v1\LikeController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/poems/{poem}', [PoemController::class, 'show'])->name('api.poems.show');



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/poems/{poem}/comments', [CommentController::class, 'store']);
    Route::post('/comments/{comment}/votes',[CommentController::class, 'vote']);
    Route::post('/poems/{poem}/like', [LikeController::class, 'store']);
});
