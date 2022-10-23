<?php

use App\Http\Controllers\ArticleController;
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
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

/**
 * Routes for logged users
 */
Route::get('/articles', [ArticleController::class, 'showArticles']);
Route::get('/articles/{userId}', [ArticleController::class, 'showArticlesByUser']);
Route::post('/articles', [ArticleController::class, 'createArticle']);
Route::put('/articles/{id}', [ArticleController::class, 'editArticle']);
Route::delete('/articles/{id}', [ArticleController::class, 'deleteArticle']);

Route::get('/categories', [CategoryController::class, 'getTopCategories']);
