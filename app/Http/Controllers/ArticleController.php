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
        $keyword = $request->get('keyword');

        $articles = $keyword === null ? Article::all()->toArray() : Article::showFilteredData($keyword);

        return json_encode($articles);
    }
}
