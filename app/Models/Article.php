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
}
