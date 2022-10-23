<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return response()->json(['data' => $articles, 'status' => 200]);
    }

    /**
     * @return false|string
     */
    public function showArticlesByUser(): bool|string
    {
        $user = auth()->userOrFail();

        $articles = $user->articles;

        return response()->json(['data' => $articles, 'status' => 200]);
    }

    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function createArticle(Request $request): bool|string
    {
        $articleData = $request->post();

        $validator = Validator::make($articleData, [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Not all arguments are provided']);
        }

        try {
            Article::addArticle($articleData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'status' => 500]);
        }
    }

    /**
     * @param int $articleId
     * @param Request $request
     *
     * @return bool|string
     */
    public function editArticle(Request $request, int $articleId): bool|string
    {
        $articleData = $request->all();

        $validator = Validator::make($articleData, [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::editArticle($articleId, $articleData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function voteForArticle(Request $request): bool|string
    {
        $voteData = $request->all();

        $validator = Validator::make($voteData, [
            'article_id' => 'required',
            'vote' => 'required|in:up,down'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::voteForArticle($voteData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    /**
     * @param int $articleId
     *
     * @return string
     */
    public function deleteArticle(int $articleId): string
    {
        $user = auth()->userOrFail();

        $article = $user->articles->find($articleId);

        if ($article !== null) {
            $article->delete();

            return response()->json(['message' => 'success', 'status' => 200]);
        }

        return response()->json(['message' => 'article with provided id not found', 'status' => 404]);
    }
}
