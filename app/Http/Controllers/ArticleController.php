<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleController extends Controller
{
    /**
     * @return Collection
     */
    public function showArticles(): Collection
    {
        return Article::all();
    }
}
