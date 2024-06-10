<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controler
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/', [ProfileController::class, 'update']);
    });

    Route::prefix('item')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::get('/history', [ItemController::class, 'history']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::post('/return/{id}', [ItemController::class, 'return']);
    });

    Route::prefix('forum-post')->group(function () {
        Route::get('/', [ForumPostController::class, 'index']);
        Route::post('/', [ForumPostController::class, 'store']);
        Route::get('/{id}', [ForumPostController::class, 'show']);
        Route::post('/{postId}/comments', [ForumReplyController::class, 'store']);
    });
});
