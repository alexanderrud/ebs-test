<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @return bool|string
     */
    public function getTop(): bool|string
    {
        $topCategories = Category::getTop();

        return response()->json(['data' => $topCategories, 'status' => 200]);
    }
}
