<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $categoryNames = [
            ['name' => 'sport'],
            ['name' => 'music'],
            ['name' => 'politics'],
            ['name' => 'social'],
            ['name' => 'cooking'],
            ['name' => 'movies'],
        ];

        DB::table('categories')->insert($categoryNames);
    }
}
