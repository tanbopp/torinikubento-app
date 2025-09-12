<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil kategori yang diperlukan
        $bentoCategory = Category::where('name', 'Bento Box')->first();
        $sideDishCategory = Category::where('name', 'Side Dish')->first();
        $donburiCategory = Category::where('name', 'Donburi')->first();
        $ramenCategory = Category::where('name', 'Ramen')->first();

        // Buat produk Chicken Katsu Bento
        $chickenKatsuBento = Product::create([
            'category_id' => $bentoCategory->id,
            'name' => 'Chicken Katsu Bento',
            'name_japanese' => 'チキンカツ弁当',
            'description' => 'Crispy breaded chicken cutlet served with steamed rice, pickled vegetables, and miso soup. A classic Japanese comfort food.',
            'allergen_info' => json_encode(['gluten', 'egg', 'soy']),
            'base_price' => 55000.00,
            'promo_price' => null,
            'is_active' => true,
            'is_available' => true,
            'is_seasonal' => false,
            'is_limited_edition' => false,
            'daily_limit' => null,
            'sort_order' => 100,
            'preparation_time' => 15,
            'nutritional_info' => json_encode([
                'calories' => 680,
                'protein' => 32,
                'carbs' => 65,
                'fat' => 28,
                'fiber' => 4
            ]),
            'spice_level' => 0,
            'cost_price' => 0, // Will be calculated based on ingredients
            'margin_percentage' => 0, // Will be calculated
            'popularity_score' => 85
        ]);

        // Buat produk Chicken Katsu (side dish)
        $chickenKatsu = Product::create([
            'category_id' => $sideDishCategory->id,
            'name' => 'Chicken Katsu',
            'name_japanese' => 'チキンカツ',
            'description' => 'Crispy breaded chicken cutlet served with tonkatsu sauce',
            'allergen_info' => json_encode(['gluten', 'egg']),
            'base_price' => 35000.00,
            'promo_price' => null,
            'is_active' => true,
            'is_available' => true,
            'is_seasonal' => false,
            'is_limited_edition' => false,
            'daily_limit' => null,
            'sort_order' => 90,
            'preparation_time' => 12,
            'nutritional_info' => json_encode([
                'calories' => 420,
                'protein' => 28,
                'carbs' => 25,
                'fat' => 22,
                'fiber' => 2
            ]),
            'spice_level' => 0,
            'cost_price' => 0, // Will be calculated based on ingredients
            'margin_percentage' => 0, // Will be calculated
            'popularity_score' => 78
        ]);

        // Buat produk Chicken Teriyaki Donburi
        $chickenTeriyakiDonburi = Product::create([
            'category_id' => $donburiCategory->id,
            'name' => 'Chicken Teriyaki Donburi',
            'name_japanese' => 'チキン照り焼き丼',
            'description' => 'Grilled chicken glazed with sweet teriyaki sauce over steamed rice',
            'allergen_info' => json_encode(['soy', 'gluten']),
            'base_price' => 45000.00,
            'promo_price' => null,
            'is_active' => true,
            'is_available' => true,
            'is_seasonal' => false,
            'is_limited_edition' => false,
            'daily_limit' => null,
            'sort_order' => 95,
            'preparation_time' => 12,
            'nutritional_info' => json_encode([
                'calories' => 580,
                'protein' => 30,
                'carbs' => 72,
                'fat' => 15,
                'fiber' => 3
            ]),
            'spice_level' => 0,
            'cost_price' => 0,
            'margin_percentage' => 0,
            'popularity_score' => 92
        ]);

        // Buat produk Salmon Teriyaki Bento
        $salmonTeriyakiBento = Product::create([
            'category_id' => $bentoCategory->id,
            'name' => 'Salmon Teriyaki Bento',
            'name_japanese' => 'サーモン照り焼き弁当',
            'description' => 'Grilled salmon with teriyaki glaze, served with rice, pickled vegetables, and miso soup',
            'allergen_info' => json_encode(['fish', 'soy', 'gluten']),
            'base_price' => 75000.00,
            'promo_price' => null,
            'is_active' => true,
            'is_available' => true,
            'is_seasonal' => false,
            'is_limited_edition' => false,
            'daily_limit' => 20,
            'sort_order' => 90,
            'preparation_time' => 18,
            'nutritional_info' => json_encode([
                'calories' => 720,
                'protein' => 35,
                'carbs' => 68,
                'fat' => 32,
                'fiber' => 4
            ]),
            'spice_level' => 0,
            'cost_price' => 0,
            'margin_percentage' => 0,
            'popularity_score' => 88
        ]);

        // Attach ingredients untuk Chicken Katsu Bento
        $this->attachIngredientsToProduct($chickenKatsuBento, [
            'Chicken Breast' => ['quantity' => 150, 'unit' => 'gram'],
            'Japanese Rice' => ['quantity' => 200, 'unit' => 'gram'],
            'Bread Crumbs' => ['quantity' => 30, 'unit' => 'gram'],
            'Eggs' => ['quantity' => 1, 'unit' => 'pcs'],
            'All Purpose Flour' => ['quantity' => 20, 'unit' => 'gram'],
            'Vegetable Oil' => ['quantity' => 30, 'unit' => 'ml'],
            'Cabbage' => ['quantity' => 50, 'unit' => 'gram'],
            'Carrot' => ['quantity' => 30, 'unit' => 'gram'],
            'Cucumber' => ['quantity' => 30, 'unit' => 'gram'],
            'Sesame Oil' => ['quantity' => 5, 'unit' => 'ml'],
            'Rice Vinegar' => ['quantity' => 10, 'unit' => 'ml'],
            'Miso Paste' => ['quantity' => 15, 'unit' => 'gram'],
            'Dashi Stock' => ['quantity' => 150, 'unit' => 'ml'],
            'Tonkatsu Sauce' => ['quantity' => 20, 'unit' => 'ml']
        ]);

        // Attach ingredients untuk Chicken Katsu (side dish)
        $this->attachIngredientsToProduct($chickenKatsu, [
            'Chicken Breast' => ['quantity' => 120, 'unit' => 'gram'],
            'Bread Crumbs' => ['quantity' => 25, 'unit' => 'gram'],
            'Eggs' => ['quantity' => 1, 'unit' => 'pcs'],
            'All Purpose Flour' => ['quantity' => 15, 'unit' => 'gram'],
            'Vegetable Oil' => ['quantity' => 25, 'unit' => 'ml'],
            'Cabbage' => ['quantity' => 30, 'unit' => 'gram'],
            'Tonkatsu Sauce' => ['quantity' => 20, 'unit' => 'ml']
        ]);

        // Attach ingredients untuk Chicken Teriyaki Donburi
        $this->attachIngredientsToProduct($chickenTeriyakiDonburi, [
            'Chicken Breast' => ['quantity' => 180, 'unit' => 'gram'],
            'Japanese Rice' => ['quantity' => 250, 'unit' => 'gram'],
            'Soy Sauce' => ['quantity' => 30, 'unit' => 'ml'],
            'Mirin' => ['quantity' => 20, 'unit' => 'ml'],
            'Green Onion' => ['quantity' => 10, 'unit' => 'gram'],
            'Sesame Oil' => ['quantity' => 5, 'unit' => 'ml'],
            'Vegetable Oil' => ['quantity' => 15, 'unit' => 'ml']
        ]);

        // Attach ingredients untuk Salmon Teriyaki Bento
        $this->attachIngredientsToProduct($salmonTeriyakiBento, [
            'Salmon Fillet' => ['quantity' => 150, 'unit' => 'gram'],
            'Japanese Rice' => ['quantity' => 200, 'unit' => 'gram'],
            'Soy Sauce' => ['quantity' => 25, 'unit' => 'ml'],
            'Mirin' => ['quantity' => 20, 'unit' => 'ml'],
            'Cabbage' => ['quantity' => 50, 'unit' => 'gram'],
            'Carrot' => ['quantity' => 30, 'unit' => 'gram'],
            'Cucumber' => ['quantity' => 30, 'unit' => 'gram'],
            'Sesame Oil' => ['quantity' => 5, 'unit' => 'ml'],
            'Rice Vinegar' => ['quantity' => 10, 'unit' => 'ml'],
            'Miso Paste' => ['quantity' => 15, 'unit' => 'gram'],
            'Dashi Stock' => ['quantity' => 150, 'unit' => 'ml'],
            'Green Onion' => ['quantity' => 10, 'unit' => 'gram'],
            'Vegetable Oil' => ['quantity' => 15, 'unit' => 'ml']
        ]);

        // Update cost price dan margin untuk semua produk
        $this->calculateProductCosts([$chickenKatsuBento, $chickenKatsu, $chickenTeriyakiDonburi, $salmonTeriyakiBento]);
    }

    /**
     * Attach ingredients to product with quantity
     */
    private function attachIngredientsToProduct(Product $product, array $ingredients): void
    {
        foreach ($ingredients as $ingredientName => $details) {
            $ingredient = Ingredient::where('name', $ingredientName)->first();
            
            if ($ingredient) {
                $product->ingredients()->attach($ingredient->id, [
                    'quantity' => $details['quantity'],
                    'unit' => $details['unit'],
                    'is_optional' => false,
                    'cost_per_unit' => $ingredient->cost_per_unit,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Calculate product costs based on ingredients
     */
    private function calculateProductCosts(array $products): void
    {
        foreach ($products as $product) {
            $totalCost = 0;
            
            foreach ($product->ingredients as $ingredient) {
                $ingredientCost = $ingredient->pivot->quantity * $ingredient->pivot->cost_per_unit;
                $totalCost += $ingredientCost;
            }
            
            // Update cost price dan margin
            $marginPercentage = (($product->base_price - $totalCost) / $product->base_price) * 100;
            
            $product->update([
                'cost_price' => $totalCost,
                'margin_percentage' => $marginPercentage
            ]);
        }
    }
}
