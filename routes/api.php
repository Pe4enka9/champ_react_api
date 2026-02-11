<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/boards', [BoardController::class, 'index']);
Route::get('/board/{board:hash}', [BoardController::class, 'showHash']);
Route::get('/boards/{board:public_link}/public', [BoardController::class, 'showPublicLink']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/boards', [UserController::class, 'boards']);

    Route::post('/boards', [BoardController::class, 'store']);
    Route::get('/boards/{board}', [BoardController::class, 'show']);
    Route::patch('/boards/{board}', [BoardController::class, 'update']);
    Route::post('/boards/{board}/access', [BoardController::class, 'access']);
    Route::post('/boards/{board}/make-public', [BoardController::class, 'makePublic']);
    Route::post('/boards/{board}/make-private', [BoardController::class, 'makePrivate']);
    Route::post('/boards/{board}/like', [BoardController::class, 'like']);
    Route::post('/boards/{board}/generate-link', [BoardController::class, 'generateLink']);
});
