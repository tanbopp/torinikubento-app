<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        // Check permission
        if (!Auth::user()->role->hasPermission('view_categories')) {
            abort(403, 'Unauthorized access');
        }

        $categories = Category::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('name_japanese', 'like', "%{$search}%");
            })
            ->when($request->status !== null, function($query) use ($request) {
                $query->where('is_active', $request->status);
            })
            ->ordered()
            ->paginate(15);

        return view('main.manage-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        if (!Auth::user()->role->hasPermission('create_categories')) {
            abort(403, 'Unauthorized access');
        }

        return view('main.manage-categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        if (!Auth::user()->role->hasPermission('create_categories')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'category_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $validated['image_path'] = $image->storeAs('categories', $filename, 'public');
        }

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        if (!Auth::user()->role->hasPermission('view_categories')) {
            abort(403, 'Unauthorized access');
        }

        $category->load(['products' => function($query) {
            $query->active()->ordered();
        }]);

        return view('main.manage-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        if (!Auth::user()->role->hasPermission('edit_categories')) {
            abort(403, 'Unauthorized access');
        }

        return view('main.manage-categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        if (!Auth::user()->role->hasPermission('edit_categories')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'name_japanese' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $image = $request->file('image');
            $filename = 'category_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $validated['image_path'] = $image->storeAs('categories', $filename, 'public');
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        if (!Auth::user()->role->hasPermission('delete_categories')) {
            abort(403, 'Unauthorized access');
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category with existing products.');
        }

        // Delete image if exists
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        if (!Auth::user()->role->hasPermission('edit_categories')) {
            abort(403, 'Unauthorized access');
        }

        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Category {$status} successfully.");
    }
}
