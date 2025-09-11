<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of ingredients
     */
    public function index(Request $request)
    {
        // Check permission
        if (!Auth::user()->role->hasPermission('view_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $ingredients = Ingredient::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('name_japanese', 'like', "%{$search}%");
            })
            ->when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->status !== null, function($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->when($request->stock_filter === 'low', function($query) {
                $query->lowStock();
            })
            ->when($request->stock_filter === 'near_expiry', function($query) {
                $query->nearExpiry();
            })
            ->orderBy('name')
            ->paginate(15);

        $types = Ingredient::getTypes();
        
        return view('main.manage-ingredients.index', compact('ingredients', 'types'));
    }

    /**
     * Show the form for creating a new ingredient
     */
    public function create()
    {
        if (!Auth::user()->role->hasPermission('create_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $types = Ingredient::getTypes();
        $units = Ingredient::getUnits();
        
        return view('main.manage-ingredients.create', compact('types', 'units'));
    }

    /**
     * Store a newly created ingredient in storage
     */
    public function store(Request $request)
    {
        if (!Auth::user()->role->hasPermission('create_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients',
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(array_keys(Ingredient::getTypes()))],
            'unit' => ['required', Rule::in(array_keys(Ingredient::getUnits()))],
            'cost_per_unit' => 'required|numeric|min:0|max:999999.9999',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'maximum_stock' => 'required|numeric|min:0',
            'supplier_info' => 'nullable|array',
            'shelf_life_days' => 'nullable|integer|min:1',
            'storage_instruction' => 'nullable|string',
            'allergen_info' => 'nullable|array',
            'nutritional_value' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        // Validate that maximum_stock >= minimum_stock
        if ($validated['maximum_stock'] < $validated['minimum_stock']) {
            return back()->withErrors(['maximum_stock' => 'Maximum stock must be greater than or equal to minimum stock.'])->withInput();
        }

        Ingredient::create($validated);

        return redirect()->route('ingredients.index')->with('success', 'Ingredient created successfully.');
    }

    /**
     * Display the specified ingredient
     */
    public function show(Ingredient $ingredient)
    {
        if (!Auth::user()->role->hasPermission('view_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        // Load products that use this ingredient with necessary relationships
        $ingredient->load(['products.category', 'products' => function($query) {
            $query->with('category');
        }]);

        return view('main.manage-ingredients.show', compact('ingredient'));
    }

    /**
     * Show the form for editing the specified ingredient
     */
    public function edit(Ingredient $ingredient)
    {
        if (!Auth::user()->role->hasPermission('edit_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $types = Ingredient::getTypes();
        $units = Ingredient::getUnits();
        
        return view('main.manage-ingredients.edit', compact('ingredient', 'types', 'units'));
    }

    /**
     * Update the specified ingredient in storage
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        if (!Auth::user()->role->hasPermission('edit_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('ingredients')->ignore($ingredient->id)],
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(array_keys(Ingredient::getTypes()))],
            'unit' => ['required', Rule::in(array_keys(Ingredient::getUnits()))],
            'cost_per_unit' => 'required|numeric|min:0|max:999999.9999',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'maximum_stock' => 'required|numeric|min:0',
            'supplier_info' => 'nullable|array',
            'shelf_life_days' => 'nullable|integer|min:1',
            'storage_instruction' => 'nullable|string',
            'allergen_info' => 'nullable|array',
            'nutritional_value' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        // Validate that maximum_stock >= minimum_stock
        if ($validated['maximum_stock'] < $validated['minimum_stock']) {
            return back()->withErrors(['maximum_stock' => 'Maximum stock must be greater than or equal to minimum stock.'])->withInput();
        }

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')->with('success', 'Ingredient updated successfully.');
    }

    /**
     * Remove the specified ingredient from storage
     */
    public function destroy(Ingredient $ingredient)
    {
        if (!Auth::user()->role->hasPermission('delete_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        // Check if ingredient is used in any products
        if ($ingredient->products()->exists()) {
            return redirect()->route('ingredients.index')->with('error', 'Cannot delete ingredient that is used in products.');
        }

        $ingredient->delete();

        return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted successfully.');
    }

    /**
     * Display ingredients with low stock
     */
    public function lowStock()
    {
        if (!Auth::user()->role->hasPermission('view_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $ingredients = Ingredient::lowStock()
            ->active()
            ->orderBy('name')
            ->paginate(15);

        return view('main.manage-ingredients.low-stock', compact('ingredients'));
    }

    /**
     * Display ingredients near expiry
     */
    public function nearExpiry()
    {
        if (!Auth::user()->role->hasPermission('view_ingredients')) {
            abort(403, 'Unauthorized access');
        }

        $ingredients = Ingredient::nearExpiry()
            ->active()
            ->with('stockEntries')
            ->orderBy('name')
            ->paginate(15);

        return view('main.manage-ingredients.near-expiry', compact('ingredients'));
    }
}
