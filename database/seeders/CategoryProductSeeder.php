<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'slug');

        $mapping = [
            'kopi' => 'kopi',
            'non-kopi' => 'non-kopi',
            'pastry' => 'pastry',
            'dessert' => 'dessert',
            'makanan' => 'makanan',
            'snack' => 'snack',
        ];

        foreach ($mapping as $categorySlug => $productCategory) {
            if (isset($categories[$categorySlug])) {
                Product::where('category', $productCategory)
                    ->whereNull('category_id')
                    ->update(['category_id' => $categories[$categorySlug]]);
            }
        }
    }
}
