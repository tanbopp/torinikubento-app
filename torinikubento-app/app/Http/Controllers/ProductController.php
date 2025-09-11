<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Addon;
use App\Models\OtherCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        // Check permission based on role
        $user = Auth::user();
        if (!$user->role->hasPermission('view_products')) {
            abort(403, 'Unauthorized access');
        }

        // Sortable columns mapping
        $sortableColumns = [
            'name' => 'name',
            'category_id' => 'category_id',
            'base_price' => 'base_price',
            'cost_price' => 'cost_price',
            'is_active' => 'is_active',
            'is_available' => 'is_available',
            'is_seasonal' => 'is_seasonal',
            'created_at' => 'created_at'
        ];

        $sort = $request->get('sort', 'sort_order');
        $direction = $request->get('direction', 'asc');

        // Validate sort column and direction
        if (!array_key_exists($sort, $sortableColumns)) {
            $sort = 'sort_order';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        $query = Product::with(['category', 'variants', 'ingredients', 'otherCosts'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('name_japanese', 'like', "%{$search}%");
            })
            ->when($request->category_id, function($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->status !== null, function($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->when($request->availability !== null, function($query) use ($request) {
                $query->where('is_available', $request->availability);
            })
            ->when($request->seasonal !== null, function($query) use ($request) {
                $query->where('is_seasonal', $request->seasonal);
            });

        // Apply sorting
        if ($sort === 'category_id') {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $direction)
                  ->select('products.*');
        } else if ($sort === 'sort_order') {
            $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
        } else {
            $query->orderBy($sortableColumns[$sort], $direction);
            
            // Add secondary sort for consistency
            if ($sort !== 'name') {
                $query->orderBy('name', 'asc');
            }
        }

        $products = $query->paginate(20);

        $categories = Category::active()->ordered()->get();

        // Check which columns have data
        $columnVisibility = [
            'has_japanese_names' => $products->getCollection()->contains(function($product) {
                return !empty($product->name_japanese);
            }),
            'has_spice_levels' => $products->getCollection()->contains(function($product) {
                return $product->spice_level > 0;
            }),
            'has_promo_prices' => $products->getCollection()->contains(function($product) {
                return !empty($product->promo_price) && $product->promo_price < $product->base_price;
            }),
            'has_taxes' => $products->getCollection()->contains(function($product) {
                return !empty($product->tax) && $product->tax->is_active;
            }),
            'has_seasonal_products' => $products->getCollection()->contains(function($product) {
                return $product->is_seasonal;
            }),
            'has_limited_products' => $products->getCollection()->contains(function($product) {
                return $product->is_limited_edition;
            }),
            'has_daily_limits' => $products->getCollection()->contains(function($product) {
                return !empty($product->daily_limit);
            }),
            'has_inactive_products' => $products->getCollection()->contains(function($product) {
                return !$product->is_active;
            }),
            'has_unavailable_products' => $products->getCollection()->contains(function($product) {
                return !$product->is_available;
            })
        ];

        return view('main.manage-products.index', compact('products', 'categories', 'columnVisibility'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('create_products')) {
            abort(403, 'Unauthorized access');
        }

        $categories = Category::active()->ordered()->get();
        $ingredients = Ingredient::active()->orderBy('name')->get();
        $addons = Addon::active()->orderBy('name')->get();
        $otherCosts = OtherCost::active()->orderBy('name')->get();

        return view('main.manage-products.create', compact('categories', 'ingredients', 'addons', 'otherCosts'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('create_products')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'allergen_info' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cropped_image' => 'nullable|string', // Base64 data dari cropper
            'base_price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_seasonal' => 'boolean',
            'is_limited_edition' => 'boolean',
            'daily_limit' => 'nullable|integer|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'spice_level' => 'nullable|integer|between:0,5',
            'season_start_date' => 'nullable|date',
            'season_end_date' => 'nullable|date|after:season_start_date',
            'nutritional_info' => 'nullable|array',
            'other_costs' => 'nullable|array',
            'other_costs.*' => 'exists:other_costs,id',
            'other_cost_values' => 'nullable|array',
            'other_cost_values.*' => 'nullable|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.ingredient_id' => 'required_with:ingredients|exists:ingredients,id',
            'ingredients.*.quantity' => 'required_with:ingredients|numeric|min:0.01',
            'ingredients.*.unit' => 'required_with:ingredients|string',
            'ingredients.*.is_optional' => 'boolean',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id'
        ]);

        DB::beginTransaction();
        try {
            // Handle image upload - prioritas cropped image dari modal cropper
            if ($request->filled('cropped_image')) {
                // Handle cropped image (base64 data)
                $base64Image = $request->cropped_image;
                
                // Remove header dari base64 string
                $image_parts = explode(";base64,", $base64Image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                
                $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image_type;
                $filepath = 'products/' . $filename;
                
                // Simpan ke storage
                Storage::disk('public')->put($filepath, $image_base64);
                $validated['image_path'] = $filepath;
                
            } elseif ($request->hasFile('image')) {
                // Handle regular file upload sebagai fallback
                $image = $request->file('image');
                $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $validated['image_path'] = $image->storeAs('products', $filename, 'public');
            }

            $product = Product::create($validated);

            // Attach ingredients (BOM)
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $ingredientData) {
                    $product->ingredients()->attach($ingredientData['ingredient_id'], [
                        'quantity' => $ingredientData['quantity'],
                        'unit' => $ingredientData['unit'],
                        'is_optional' => $ingredientData['is_optional'] ?? false
                    ]);
                }
            }

            // Attach addons
            if ($request->has('addons')) {
                $product->addons()->attach($request->addons);
            }

            // Attach other costs
            if ($request->has('other_costs')) {
                foreach ($request->other_costs as $index => $otherCostId) {
                    $customValue = $request->other_cost_values[$index] ?? null;
                    $product->otherCosts()->attach($otherCostId, [
                        'custom_value' => $customValue
                    ]);
                }
            }

            // Update cost price and margin - always auto-calculate from BOM and other costs
            $product->recalculateCostPrice();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            // Delete uploaded image if exists
            if (isset($validated['image_path'])) {
                Storage::disk('public')->delete($validated['image_path']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('view_products')) {
            abort(403, 'Unauthorized access');
        }

        $product->load([
            'category', 
            'tax',
            'variants', 
            'ingredients' => function($query) {
                $query->orderBy('name');
            }, 
            'addons' => function($query) {
                $query->orderBy('name');
            },
            'bundleItems',
            'bundles'
        ]);

        return view('main.manage-products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $categories = Category::active()->ordered()->get();
        $ingredients = Ingredient::active()->orderBy('name')->get();
        $addons = Addon::active()->orderBy('name')->get();
        $otherCosts = OtherCost::active()->orderBy('name')->get();

        $product->load(['ingredients', 'addons', 'variants', 'otherCosts']);

        return view('main.manage-products.edit', compact('product', 'categories', 'ingredients', 'addons', 'otherCosts'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'allergen_info' => 'nullable|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cropped_image' => 'nullable|string', // Base64 data dari cropper
            'remove_image' => 'nullable|boolean', // Checkbox untuk hapus gambar
            'base_price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_available' => 'boolean',
            'is_seasonal' => 'boolean',
            'is_limited_edition' => 'boolean',
            'daily_limit' => 'nullable|integer|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'spice_level' => 'nullable|integer|between:0,5',
            'season_start_date' => 'nullable|date',
            'season_end_date' => 'nullable|date|after:season_start_date',
            'nutritional_info' => 'nullable|array',
            'other_costs' => 'nullable|array',
            'other_costs.*' => 'exists:other_costs,id',
            'other_cost_values' => 'nullable|array',
            'other_cost_values.*' => 'nullable|numeric|min:0',
            'ingredients' => 'nullable|array',
            'ingredients.*.ingredient_id' => 'required_with:ingredients|exists:ingredients,id',
            'ingredients.*.quantity' => 'required_with:ingredients|numeric|min:0.01',
            'ingredients.*.unit' => 'required_with:ingredients|string',
            'ingredients.*.is_optional' => 'boolean',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id'
        ]);

        DB::beginTransaction();
        try {
            // Handle image removal
            if ($request->boolean('remove_image')) {
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $validated['image_path'] = null;
            }
            // Handle cropped image upload
            elseif ($request->filled('cropped_image')) {
                // Delete old image if exists
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                // Handle cropped image (base64 data)
                $base64Image = $request->cropped_image;
                
                // Remove header dari base64 string
                $image_parts = explode(";base64,", $base64Image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                
                $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image_type;
                $filepath = 'products/' . $filename;
                
                // Simpan ke storage
                Storage::disk('public')->put($filepath, $image_base64);
                $validated['image_path'] = $filepath;
            }
            // Handle regular file upload sebagai fallback
            elseif ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }

                $image = $request->file('image');
                $filename = 'product_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $validated['image_path'] = $image->storeAs('products', $filename, 'public');
            }

            $product->update($validated);

            // Sync ingredients (BOM)
            if ($request->has('ingredients')) {
                $ingredientSync = [];
                foreach ($request->ingredients as $ingredientData) {
                    $ingredientSync[$ingredientData['ingredient_id']] = [
                        'quantity' => $ingredientData['quantity'],
                        'unit' => $ingredientData['unit'],
                        'is_optional' => $ingredientData['is_optional'] ?? false
                    ];
                }
                $product->ingredients()->sync($ingredientSync);
            } else {
                $product->ingredients()->detach();
            }

            // Sync addons
            if ($request->has('addons')) {
                $product->addons()->sync($request->addons);
            } else {
                $product->addons()->detach();
            }

            // Sync other costs
            if ($request->has('other_costs')) {
                $otherCostSync = [];
                foreach ($request->other_costs as $index => $otherCostId) {
                    $customValue = $request->other_cost_values[$index] ?? null;
                    $otherCostSync[$otherCostId] = [
                        'custom_value' => $customValue
                    ];
                }
                $product->otherCosts()->sync($otherCostSync);
            } else {
                $product->otherCosts()->detach();
            }

            // Update cost price and margin - always auto-calculate from BOM and other costs
            $product->recalculateCostPrice();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('delete_products')) {
            abort(403, 'Unauthorized access');
        }

        // Delete image if exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Product {$status} successfully.");
    }

    /**
     * Toggle product availability
     */
    public function toggleAvailability(Product $product)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $product->update(['is_available' => !$product->is_available]);

        $status = $product->is_available ? 'available' : 'unavailable';
        
        return redirect()->back()
            ->with('success', "Product marked as {$status} successfully.");
    }

    /**
     * Get menu engineering analytics
     */
    public function menuEngineering()
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('view_analytics')) {
            abort(403, 'Unauthorized access');
        }

        $products = Product::active()
            ->with('category')
            ->select([
                'id', 'name', 'category_id', 'base_price', 'cost_price', 
                'margin_percentage', 'popularity_score'
            ])
            ->get()
            ->map(function ($product) {
                // Menu Engineering Classification
                $isHighProfit = $product->margin_percentage > 30; // Configurable threshold
                $isPopular = $product->popularity_score > 50; // Configurable threshold
                
                if ($isPopular && $isHighProfit) {
                    $classification = 'Star';
                    $color = 'success';
                } elseif (!$isPopular && $isHighProfit) {
                    $classification = 'Puzzle';
                    $color = 'warning';
                } elseif ($isPopular && !$isHighProfit) {
                    $classification = 'Plowhorse';
                    $color = 'info';
                } else {
                    $classification = 'Dog';
                    $color = 'danger';
                }

                $product->classification = $classification;
                $product->color = $color;
                
                return $product;
            });

        return view('main.manage-products.menu-engineering', compact('products'));
    }

    /**
     * Bulk update cost prices based on current ingredient costs
     */
    public function updateAllCostPrices()
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $products = Product::with('ingredients')->get();
        $updated = 0;

        foreach ($products as $product) {
            $oldCost = $product->cost_price;
            $product->updateCostPrice();
            
            if ($product->cost_price != $oldCost) {
                $updated++;
            }
        }

        return redirect()->back()
            ->with('success', "Updated cost prices for {$updated} products.");
    }

    /**
     * Display BOM/Recipes view for kitchen staff
     */
    public function bomView(Request $request)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('view_product_recipes')) {
            abort(403, 'Unauthorized access');
        }

        $products = Product::with(['category', 'ingredients' => function($query) {
            $query->with('ingredient');
        }])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('name_japanese', 'like', "%{$search}%");
            })
            ->when($request->category_id, function($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        $categories = Category::active()->ordered()->get();

        return view('main.manage-products.bom', compact('products', 'categories'));
    }
}
