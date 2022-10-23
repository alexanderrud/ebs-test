<?php

namespace App\Http\Controllers;

use App\Models\Article;
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

        return json_encode(['data' => $articles, 'status' => 200]);
    }

    /**
     * @param int $userId
     *
     * @return false|string
     */
    public function showArticlesByUser(int $userId): bool|string
    {
        $articles = Article::where('created_by', $userId)->get()->toArray();

        return json_encode(['data' => $articles, 'status' => 200]);
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
            return json_encode(['message' => 'Not all arguments are provided']);
        }

        try {
            Article::addArticle($articleData);

            return json_encode(['message' => 'success', 'status' => 200]);
        } catch (\Exception $exception) {
            return json_encode(['message' => $exception->getMessage(), 'status' => 500]);
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
            return json_encode(['message' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::editArticle($articleId, $articleData);

            return json_encode(['message' => 'success', 'status' => 200]);
        } catch (\Exception $exception) {
            return json_encode(['message' => $exception->getMessage(), 'status' => $exception->getCode()]);
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
            return json_encode(['message' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::voteForArticle($voteData);

            return json_encode(['message' => 'success', 'status' => 200]);
        } catch (\Exception $exception) {
            return json_encode(['message' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    /**
     * @param int $articleId
     *
     * @return string
     */
    public function deleteArticle(int $articleId): string
    {
        $article = Article::find($articleId);

        if ($article !== null) {
            $article->delete();

            return json_encode(['message' => 'success', 'status' => 200]);
        }

        return json_encode(['message' => 'article with provided id not found', 'status' => 404]);
    }
}
