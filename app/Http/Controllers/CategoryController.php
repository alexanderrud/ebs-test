<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @return bool|string
     */
    public function getTopCategories(): bool|string
    {
        $topCategories = Category::getTopCategories();

        return json_encode(['data' => $topCategories, 'status' => 200]);
    }
}
