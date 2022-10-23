<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

/**
 * Endpoints for logged users
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'me'
], static function () {
    Route::get('/articles', [ArticleController::class, 'getByUser']);
    Route::post('/articles', [ArticleController::class, 'create']);
    Route::put('/articles/{id}', [ArticleController::class, 'edit']);
    Route::put('/articles', [ArticleController::class, 'vote']);
    Route::delete('/articles/{id}', [ArticleController::class, 'delete']);
});

/**
 * Endpoints for guests
 */
Route::get('/articles', [ArticleController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'getTop']);
