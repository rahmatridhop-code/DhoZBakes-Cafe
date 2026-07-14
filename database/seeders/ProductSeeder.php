<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // KOPI (category_id will be set after categories are seeded)
            ['name' => 'Espresso', 'category' => 'kopi', 'price' => 28000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Americano', 'category' => 'kopi', 'price' => 32000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Cappuccino', 'category' => 'kopi', 'price' => 38000, 'emoji' => '☕', 'badge' => 'Populer'],
            ['name' => 'Cafe Latte', 'category' => 'kopi', 'price' => 40000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Caramel Macchiato', 'category' => 'kopi', 'price' => 45000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Mocha', 'category' => 'kopi', 'price' => 45000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Affogato', 'category' => 'kopi', 'price' => 42000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Cold Brew', 'category' => 'kopi', 'price' => 40000, 'emoji' => '🧊', 'badge' => 'Favorit'],
            ['name' => 'Vietnamese Coffee', 'category' => 'kopi', 'price' => 38000, 'emoji' => '☕', 'badge' => null],
            ['name' => 'Hazelnut Latte', 'category' => 'kopi', 'price' => 45000, 'emoji' => '☕', 'badge' => null],

            // NON KOPI
            ['name' => 'Matcha Latte', 'category' => 'non-kopi', 'price' => 42000, 'emoji' => '🍵', 'badge' => 'Populer'],
            ['name' => 'Chocolate', 'category' => 'non-kopi', 'price' => 38000, 'emoji' => '🍫', 'badge' => null],
            ['name' => 'Taro Latte', 'category' => 'non-kopi', 'price' => 42000, 'emoji' => '🧋', 'badge' => null],
            ['name' => 'Thai Tea', 'category' => 'non-kopi', 'price' => 35000, 'emoji' => '🧋', 'badge' => null],
            ['name' => 'Lemon Tea', 'category' => 'non-kopi', 'price' => 28000, 'emoji' => '🍋', 'badge' => null],
            ['name' => 'Fresh Orange Juice', 'category' => 'non-kopi', 'price' => 38000, 'emoji' => '🍊', 'badge' => null],
            ['name' => 'Avocado Smoothie', 'category' => 'non-kopi', 'price' => 42000, 'emoji' => '🥑', 'badge' => 'Favorit'],
            ['name' => 'Mango Smoothie', 'category' => 'non-kopi', 'price' => 40000, 'emoji' => '🥭', 'badge' => null],
            ['name' => 'Berry Smoothie', 'category' => 'non-kopi', 'price' => 45000, 'emoji' => '🫐', 'badge' => null],
            ['name' => 'Vanilla Milkshake', 'category' => 'non-kopi', 'price' => 40000, 'emoji' => '🥛', 'badge' => null],

            // PASTRY
            ['name' => 'Butter Croissant', 'category' => 'pastry', 'price' => 32000, 'emoji' => '🥐', 'badge' => 'Favorit'],
            ['name' => 'Pain au Chocolat', 'category' => 'pastry', 'price' => 35000, 'emoji' => '🥐', 'badge' => null],
            ['name' => 'Almond Croissant', 'category' => 'pastry', 'price' => 38000, 'emoji' => '🥐', 'badge' => 'Populer'],
            ['name' => 'Danish Pastry', 'category' => 'pastry', 'price' => 35000, 'emoji' => '🥐', 'badge' => null],
            ['name' => 'Sourdough Bread', 'category' => 'pastry', 'price' => 28000, 'emoji' => '🍞', 'badge' => null],
            ['name' => 'Baguette', 'category' => 'pastry', 'price' => 25000, 'emoji' => '🥖', 'badge' => null],
            ['name' => 'Cinnamon Roll', 'category' => 'pastry', 'price' => 35000, 'emoji' => '🧇', 'badge' => 'Populer'],
            ['name' => 'Brioche', 'category' => 'pastry', 'price' => 30000, 'emoji' => '🍞', 'badge' => null],

            // DESSERT
            ['name' => 'Tiramisu', 'category' => 'dessert', 'price' => 55000, 'emoji' => '🍰', 'badge' => 'Favorit'],
            ['name' => 'New York Cheesecake', 'category' => 'dessert', 'price' => 52000, 'emoji' => '🍰', 'badge' => null],
            ['name' => 'Chocolate Lava Cake', 'category' => 'dessert', 'price' => 58000, 'emoji' => '🍫', 'badge' => 'Populer'],
            ['name' => 'Creme Brulee', 'category' => 'dessert', 'price' => 48000, 'emoji' => '🍮', 'badge' => null],
            ['name' => 'Panna Cotta', 'category' => 'dessert', 'price' => 45000, 'emoji' => '🍮', 'badge' => null],
            ['name' => 'Tart Buah', 'category' => 'dessert', 'price' => 48000, 'emoji' => '🥧', 'badge' => null],
            ['name' => 'Macaron (6pcs)', 'category' => 'dessert', 'price' => 65000, 'emoji' => '🧁', 'badge' => 'Premium'],
            ['name' => 'Brownies', 'category' => 'dessert', 'price' => 35000, 'emoji' => '🟫', 'badge' => null],

            // MAKANAN BERAT
            ['name' => 'Nasi Goreng Spesial', 'category' => 'makanan', 'price' => 52000, 'emoji' => '🍛', 'badge' => null],
            ['name' => 'Spaghetti Carbonara', 'category' => 'makanan', 'price' => 58000, 'emoji' => '🍝', 'badge' => 'Populer'],
            ['name' => 'Spaghetti Bolognese', 'category' => 'makanan', 'price' => 55000, 'emoji' => '🍝', 'badge' => null],
            ['name' => 'Chicken Steak', 'category' => 'makanan', 'price' => 65000, 'emoji' => '🍗', 'badge' => null],
            ['name' => 'Beef Steak', 'category' => 'makanan', 'price' => 95000, 'emoji' => '🥩', 'badge' => 'Premium'],
            ['name' => 'Fish & Chips', 'category' => 'makanan', 'price' => 58000, 'emoji' => '🐟', 'badge' => null],
            ['name' => 'Chicken Parmigiana', 'category' => 'makanan', 'price' => 62000, 'emoji' => '🍗', 'badge' => null],
            ['name' => 'Club Sandwich', 'category' => 'makanan', 'price' => 48000, 'emoji' => '🥪', 'badge' => null],

            // SNACK
            ['name' => 'French Fries', 'category' => 'snack', 'price' => 28000, 'emoji' => '🍟', 'badge' => null],
            ['name' => 'Loaded Fries', 'category' => 'snack', 'price' => 38000, 'emoji' => '🍟', 'badge' => 'Populer'],
            ['name' => 'Chicken Wings (6pc)', 'category' => 'snack', 'price' => 48000, 'emoji' => '🍗', 'badge' => null],
            ['name' => 'Mozzarella Sticks', 'category' => 'snack', 'price' => 42000, 'emoji' => '🧀', 'badge' => null],
            ['name' => 'Garlic Bread', 'category' => 'snack', 'price' => 25000, 'emoji' => '🍞', 'badge' => null],
            ['name' => 'Nachos', 'category' => 'snack', 'price' => 42000, 'emoji' => '🫓', 'badge' => null],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
