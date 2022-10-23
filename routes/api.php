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
    'prefixadded' => 'auth'
], static function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

/**
 * Routes for logged users
 */
Route::get('/articles/{userId}', [ArticleController::class, 'showArticlesByUser']);
Route::post('/articles', [ArticleController::class, 'createArticle']);
Route::put('/articles/{id}', [ArticleController::class, 'editArticle']);
Route::put('/articles', [ArticleController::class, 'voteForArticle']);
Route::delete('/articles/{id}', [ArticleController::class, 'deleteArticle']);

Route::get('/articles', [ArticleController::class, 'showArticles']);
Route::get('/categories', [CategoryController::class, 'getTopCategories']);
