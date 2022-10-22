<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
     * @param string $keyword
     *
     * @return array
     */
    public static function showFilteredData(string $keyword): array
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
}
