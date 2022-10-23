<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function showArticles(Request $request): bool|string
    {
        $articles = [];

        if ($request->has('keyword')) {
            $articles = Article::getByKeyword($request->get('keyword'));
        }

        if ($request->has('category_id')) {
            $articles = Article::getByCategoryId($request->get('category_id'));
        }

        return json_encode(['data' => $articles, 'status' => 200]);
    }
}
