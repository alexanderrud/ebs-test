<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function show(Request $request): bool|string
    {
        $articles = Article::all();

        if ($request->has('keyword')) {
            $articles = Article::getByKeyword($request->get('keyword'));
        }

        if ($request->has('category_id')) {
            $articles = Article::getByCategoryId($request->get('category_id'));
        }

        return response()->json(['data' => $articles, 'status' => 200]);
    }

    /**
     * @return bool|string
     */
    public function getByUser(): bool|string
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $exception) {
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }

        $articles = $user->articles;

        return response()->json(['data' => $articles, 'status' => 200]);
    }

    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function create(Request $request): bool|string
    {
        try {
            auth()->userOrFail();
        } catch (UserNotDefinedException $exception) {
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }

        $articleData = $request->post();

        $validator = Validator::make($articleData, [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::add($articleData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'status' => 500]);
        }
    }

    /**
     * @param int $articleId
     * @param Request $request
     *
     * @return bool|string
     */
    public function edit(Request $request, int $articleId): bool|string
    {
        $articleData = $request->all();

        $validator = Validator::make($articleData, [
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::edit($articleId, $articleData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool|string
     */
    public function vote(Request $request): bool|string
    {
        $voteData = $request->all();

        $validator = Validator::make($voteData, [
            'article_id' => 'required',
            'vote' => 'required|in:up,down'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Not all arguments are provided', 'status' => 500]);
        }

        try {
            Article::vote($voteData);

            return response()->json(['message' => 'success', 'status' => 200]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage(), 'status' => $exception->getCode()]);
        }
    }

    /**
     * @param int $articleId
     *
     * @return string
     */
    public function delete(int $articleId): string
    {
        $user = auth()->userOrFail();

        $article = $user->articles->find($articleId);

        if ($article !== null) {
            $article->delete();

            return response()->json(['message' => 'success', 'status' => 200]);
        }

        return response()->json(['error' => 'article with provided id not found', 'status' => 404]);
    }
}
