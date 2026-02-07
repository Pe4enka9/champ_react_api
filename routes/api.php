<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/boards', [BoardController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/boards/user', [BoardController::class, 'userBoards']);
    Route::post('/boards', [BoardController::class, 'store']);
    Route::post('/boards/{board}/access', [BoardController::class, 'storeAccess']);
    Route::post('/boards/{board}/make-public', [BoardController::class, 'makePublic']);
    Route::post('/boards/{board}/make-private', [BoardController::class, 'makePrivate']);
    Route::post('/boards/{board}/like', [BoardController::class, 'like']);

    Route::get('/boards/{board}/objects', [ObjectController::class, 'index']);
    Route::patch('/boards/{board}/objects', [ObjectController::class, 'update']);
});

Route::get('/board/{board:hash}', [BoardController::class, 'show']);
