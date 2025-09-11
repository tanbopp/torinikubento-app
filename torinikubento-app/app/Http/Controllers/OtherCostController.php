<?php

namespace App\Http\Controllers;

use App\Models\OtherCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('view_products')) {
            abort(403, 'Unauthorized access');
        }

        $query = OtherCost::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status !== null) {
            $query->where('is_active', $request->status);
        }

        $otherCosts = $query->orderBy('name')->paginate(20);

        return view('main.other-costs.index', compact('otherCosts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('create_products')) {
            abort(403, 'Unauthorized access');
        }

        return view('main.other-costs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('create_products')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        OtherCost::create($validated);

        return redirect()->route('other-costs.index')
            ->with('success', 'Biaya lain berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OtherCost $otherCost)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        return view('main.other-costs.edit', compact('otherCost'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OtherCost $otherCost)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('edit_products')) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $otherCost->update($validated);

        return redirect()->route('other-costs.index')
            ->with('success', 'Biaya lain berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OtherCost $otherCost)
    {
        $user = Auth::user();
        if (!$user->role->hasPermission('delete_products')) {
            abort(403, 'Unauthorized access');
        }

        $otherCost->delete();

        return redirect()->route('other-costs.index')
            ->with('success', 'Biaya lain berhasil dihapus.');
    }
}
