<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'created_by',
        'title',
        'description',
        'rating',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param string $keyword
     *
     * @return array
     */
    public static function getByKeyword(string $keyword): array
    {
        return DB::table('articles')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->select('articles.*', 'categories.name as category')
            ->where('articles.title', 'like', '%' . $keyword . '%')
            ->orWhere('articles.description', 'like', '%' . $keyword . '%')
            ->orderBy('articles.created_at', 'DESC')
            ->get()
            ->toArray();
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    public static function getByCategoryId(int $categoryId): array
    {
        $articles = Category::with('articles')->find($categoryId)->articles;

        return $articles->toArray();
    }

    /**
     * @param array $articleData
     *
     * @return bool
     */
    public static function addArticle(array $articleData): bool
    {
        $article = new self();

        $article->category_id = $articleData['category_id'];
        $article->created_by = $articleData['created_by'];
        $article->title = $articleData['title'];
        $article->description = $articleData['description'];

        return $article->save();
    }

    /**
     * @param int $articleId
     * @param array $articleData
     *
     * @throws \Exception
     *
     * @return bool
     */
    public static function editArticle(int $articleId, array $articleData): bool
    {
        $article = self::find($articleId);

        if ($article === null) {
            throw new \Exception('Article not found', 404);
        }

        $article->category_id = $articleData['category_id'];
        $article->title = $articleData['title'];
        $article->description = $articleData['description'];

        return $article->save();
    }

    /**
     * @param array $voteData
     *
     * @throws \Exception
     *
     * @return bool
     */
    public static function voteForArticle(array $voteData): bool
    {
        $article = self::find($voteData['article_id']);

        if ($article === null) {
            throw new \Exception('Provided article not found', 404);
        }

        if ($voteData['vote'] === 'down') {
            if ($article->rating === 0) {
                throw new \Exception('Cannot decrease article that has 0 rating', 500);
            }

            $article->rating--;
        } elseif ($voteData['vote'] === 'up') {
            $article->rating++;
        }

        return $article->save();
    }
}
