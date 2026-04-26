<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class)->only(['store', 'index']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);
});

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});