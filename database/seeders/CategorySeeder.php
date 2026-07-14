<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kopi', 'slug' => 'kopi', 'icon' => '☕', 'sort_order' => 1],
            ['name' => 'Non Kopi', 'slug' => 'non-kopi', 'icon' => '🍵', 'sort_order' => 2],
            ['name' => 'Pastry', 'slug' => 'pastry', 'icon' => '🥐', 'sort_order' => 3],
            ['name' => 'Dessert', 'slug' => 'dessert', 'icon' => '🍰', 'sort_order' => 4],
            ['name' => 'Makanan Berat', 'slug' => 'makanan', 'icon' => '🍝', 'sort_order' => 5],
            ['name' => 'Snack', 'slug' => 'snack', 'icon' => '🍪', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
