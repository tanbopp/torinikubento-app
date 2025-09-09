<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Donburi',
                'name_japanese' => '丼物',
                'description' => 'Japanese rice bowl dishes with various toppings',
                'sort_order' => 100,
                'is_active' => true
            ],
            [
                'name' => 'Ramen',
                'name_japanese' => 'ラーメン', 
                'description' => 'Japanese noodle soup in various broths',
                'sort_order' => 90,
                'is_active' => true
            ],
            [
                'name' => 'Sushi & Sashimi',
                'name_japanese' => '寿司・刺身',
                'description' => 'Fresh fish served with rice or standalone',
                'sort_order' => 80,
                'is_active' => true
            ],
            [
                'name' => 'Bento Box',
                'name_japanese' => '弁当',
                'description' => 'Complete meal sets in traditional Japanese lunchbox',
                'sort_order' => 70,
                'is_active' => true
            ],
            [
                'name' => 'Side Dish',
                'name_japanese' => 'サイドディッシュ',
                'description' => 'Gyoza, tempura, and other appetizers',
                'sort_order' => 60,
                'is_active' => true
            ],
            [
                'name' => 'Beverages',
                'name_japanese' => '飲み物',
                'description' => 'Japanese tea, soft drinks, and specialty drinks',
                'sort_order' => 50,
                'is_active' => true
            ],
            [
                'name' => 'Desserts',
                'name_japanese' => 'デザート',
                'description' => 'Mochi, dorayaki, and other Japanese sweets',
                'sort_order' => 40,
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
