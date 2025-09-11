<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            // Rice & Noodles
            [
                'name' => 'Japanese Rice',
                'name_japanese' => '日本米',
                'description' => 'Premium short-grain Japanese rice',
                'type' => 'dry',
                'unit' => 'gram',
                'cost_per_unit' => 0.015,
                'current_stock' => 20000,
                'minimum_stock' => 5000,
                'maximum_stock' => 50000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in cool, dry place',
                'is_active' => true
            ],
            [
                'name' => 'Ramen Noodles',
                'name_japanese' => 'ラーメンの麺',
                'description' => 'Fresh ramen noodles',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.02,
                'current_stock' => 5000,
                'minimum_stock' => 1000,
                'maximum_stock' => 10000,
                'shelf_life_days' => 3,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            
            // Proteins
            [
                'name' => 'Chicken Breast',
                'name_japanese' => '鶏胸肉',
                'description' => 'Fresh chicken breast fillet',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.035,
                'current_stock' => 10000,
                'minimum_stock' => 2000,
                'maximum_stock' => 15000,
                'shelf_life_days' => 2,
                'storage_instruction' => 'Keep frozen until use',
                'allergen_info' => ['poultry'],
                'is_active' => true
            ],
            [
                'name' => 'Salmon Fillet',
                'name_japanese' => 'サーモン',
                'description' => 'Fresh salmon sashimi grade',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.12,
                'current_stock' => 3000,
                'minimum_stock' => 500,
                'maximum_stock' => 5000,
                'shelf_life_days' => 1,
                'storage_instruction' => 'Keep on ice, use immediately',
                'allergen_info' => ['fish'],
                'is_active' => true
            ],
            [
                'name' => 'Beef Sukiyaki Cut',
                'name_japanese' => '牛肉すき焼き用',
                'description' => 'Thinly sliced beef for sukiyaki',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.08,
                'current_stock' => 5000,
                'minimum_stock' => 1000,
                'maximum_stock' => 8000,
                'shelf_life_days' => 2,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            [
                'name' => 'Eggs',
                'name_japanese' => '卵',
                'description' => 'Fresh chicken eggs',
                'type' => 'fresh',
                'unit' => 'pcs',
                'cost_per_unit' => 2.5,
                'current_stock' => 500,
                'minimum_stock' => 100,
                'maximum_stock' => 1000,
                'shelf_life_days' => 7,
                'storage_instruction' => 'Keep refrigerated',
                'allergen_info' => ['eggs'],
                'is_active' => true
            ],

            // Vegetables
            [
                'name' => 'Cabbage',
                'name_japanese' => 'キャベツ',
                'description' => 'Fresh cabbage for salad',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.005,
                'current_stock' => 5000,
                'minimum_stock' => 1000,
                'maximum_stock' => 8000,
                'shelf_life_days' => 7,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            [
                'name' => 'Carrot',
                'name_japanese' => '人参',
                'description' => 'Fresh carrots',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.008,
                'current_stock' => 3000,
                'minimum_stock' => 500,
                'maximum_stock' => 5000,
                'shelf_life_days' => 14,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            [
                'name' => 'Cucumber',
                'name_japanese' => 'きゅうり',
                'description' => 'Fresh cucumber',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.006,
                'current_stock' => 2000,
                'minimum_stock' => 400,
                'maximum_stock' => 3000,
                'shelf_life_days' => 7,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            [
                'name' => 'Nori Seaweed',
                'name_japanese' => '海苔',
                'description' => 'Dried seaweed sheets',
                'type' => 'dry',
                'unit' => 'gram',
                'cost_per_unit' => 0.25,
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'maximum_stock' => 2000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in dry place',
                'is_active' => true
            ],
            [
                'name' => 'Green Onion',
                'name_japanese' => 'ネギ',
                'description' => 'Fresh green onions',
                'type' => 'fresh',
                'unit' => 'gram',
                'cost_per_unit' => 0.01,
                'current_stock' => 2000,
                'minimum_stock' => 500,
                'maximum_stock' => 3000,
                'shelf_life_days' => 5,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],

            // Seasonings & Sauces
            [
                'name' => 'Soy Sauce',
                'name_japanese' => '醤油',
                'description' => 'Japanese soy sauce',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.008,
                'current_stock' => 5000,
                'minimum_stock' => 1000,
                'maximum_stock' => 10000,
                'shelf_life_days' => 730,
                'storage_instruction' => 'Store in cool place',
                'allergen_info' => ['soy', 'gluten'],
                'is_active' => true
            ],
            [
                'name' => 'Miso Paste',
                'name_japanese' => '味噌',
                'description' => 'Fermented soybean paste',
                'type' => 'dry',
                'unit' => 'gram',
                'cost_per_unit' => 0.02,
                'current_stock' => 3000,
                'minimum_stock' => 500,
                'maximum_stock' => 5000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Keep refrigerated after opening',
                'allergen_info' => ['soy'],
                'is_active' => true
            ],
            [
                'name' => 'Mirin',
                'name_japanese' => 'みりん',
                'description' => 'Japanese sweet rice wine for cooking',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.012,
                'current_stock' => 2000,
                'minimum_stock' => 500,
                'maximum_stock' => 4000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in cool place',
                'is_active' => true
            ],
            [
                'name' => 'Sesame Oil',
                'name_japanese' => 'ごま油',
                'description' => 'Pure sesame oil',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.025,
                'current_stock' => 1000,
                'minimum_stock' => 200,
                'maximum_stock' => 2000,
                'shelf_life_days' => 730,
                'storage_instruction' => 'Store in cool, dark place',
                'allergen_info' => ['sesame'],
                'is_active' => true
            ],
            [
                'name' => 'Rice Vinegar',
                'name_japanese' => '米酢',
                'description' => 'Japanese rice vinegar',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.01,
                'current_stock' => 2000,
                'minimum_stock' => 400,
                'maximum_stock' => 3000,
                'shelf_life_days' => 730,
                'storage_instruction' => 'Store in cool place',
                'is_active' => true
            ],
            [
                'name' => 'Vegetable Oil',
                'name_japanese' => '植物油',
                'description' => 'Neutral vegetable oil for frying',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.003,
                'current_stock' => 10000,
                'minimum_stock' => 2000,
                'maximum_stock' => 15000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in cool place',
                'is_active' => true
            ],
            [
                'name' => 'All Purpose Flour',
                'name_japanese' => '小麦粉',
                'description' => 'All purpose wheat flour',
                'type' => 'dry',
                'unit' => 'gram',
                'cost_per_unit' => 0.004,
                'current_stock' => 5000,
                'minimum_stock' => 1000,
                'maximum_stock' => 8000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in dry place',
                'allergen_info' => ['gluten'],
                'is_active' => true
            ],
            [
                'name' => 'Dashi Stock',
                'name_japanese' => 'だし',
                'description' => 'Japanese soup stock',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.005,
                'current_stock' => 3000,
                'minimum_stock' => 500,
                'maximum_stock' => 5000,
                'shelf_life_days' => 3,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
            [
                'name' => 'Tonkatsu Sauce',
                'name_japanese' => 'とんかつソース',
                'description' => 'Sweet and tangy sauce for katsu',
                'type' => 'liquid',
                'unit' => 'ml',
                'cost_per_unit' => 0.015,
                'current_stock' => 2000,
                'minimum_stock' => 300,
                'maximum_stock' => 3000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Store in cool place',
                'is_active' => true
            ],

            // Specialty Items
            [
                'name' => 'Bread Crumbs',
                'name_japanese' => 'パン粉',
                'description' => 'Japanese-style breadcrumbs',
                'type' => 'dry',
                'unit' => 'gram',
                'cost_per_unit' => 0.015,
                'current_stock' => 2000,
                'minimum_stock' => 500,
                'maximum_stock' => 4000,
                'shelf_life_days' => 180,
                'storage_instruction' => 'Store in dry place',
                'allergen_info' => ['gluten'],
                'is_active' => true
            ],
            [
                'name' => 'Wasabi Paste',
                'name_japanese' => 'わさび',
                'description' => 'Japanese horseradish paste',
                'type' => 'liquid',
                'unit' => 'gram',
                'cost_per_unit' => 0.05,
                'current_stock' => 500,
                'minimum_stock' => 100,
                'maximum_stock' => 1000,
                'shelf_life_days' => 365,
                'storage_instruction' => 'Keep refrigerated',
                'is_active' => true
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
