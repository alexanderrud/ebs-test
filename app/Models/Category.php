<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property Collection $articles
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    /**
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * @return array
     */
    public static function getTopCategories(): array
    {
        return DB::table('categories')
            ->join('articles', 'categories.id', '=', 'articles.category_id')
            ->select('categories.name', DB::raw('count(articles.id)'))
            ->groupBy('categories.id')
            ->orderByRaw('sum(articles.rating) DESC')
            ->havingRaw('count(articles.id) >= 2')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
