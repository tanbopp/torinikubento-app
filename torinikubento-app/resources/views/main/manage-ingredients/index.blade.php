@extends('partials.layouts.main')

@section('page-title', 'Manajemen Bahan Baku')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🥬 Manajemen Bahan Baku</h1>
                <p class="text-neutral-400">Kelola bahan baku dan inventory untuk menu resto Toriniku Bento</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('create_ingredients'))
                <a href="{{ route('ingredients.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Bahan Baku</span>
                </a>
                @endif
                <a href="{{ route('ingredients.low-stock') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                    <span>Stok Rendah</span>
                </a>
                <a href="{{ route('ingredients.near-expiry') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-clock text-sm"></i>
                    <span>Hampir Kadaluarsa</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-green-600/20 border border-green-600/30 text-green-400 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filter Section --}}
    <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Filter & Pencarian</h3>
        <form method="GET" action="{{ route('ingredients.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama bahan baku atau nama Jepang"
                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Jenis</label>
                <select name="type" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Jenis</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Status</label>
                <select name="status" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Filter Stok</label>
                <select name="stock_filter" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Stok</option>
                    <option value="low" {{ request('stock_filter') === 'low' ? 'selected' : '' }}>Stok Rendah</option>
                    <option value="near_expiry" {{ request('stock_filter') === 'near_expiry' ? 'selected' : '' }}>Hampir Kadaluarsa</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Ingredients Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Daftar Bahan Baku</h3>
        </div>
        <div class="p-6">
            @if($ingredients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-neutral-700">
                            <th class="pb-3 text-neutral-300 font-medium">Nama Bahan</th>
                            <th class="pb-3 text-neutral-300 font-medium">Jenis</th>
                            <th class="pb-3 text-neutral-300 font-medium">Stok</th>
                            <th class="pb-3 text-neutral-300 font-medium">Harga/Unit</th>
                            <th class="pb-3 text-neutral-300 font-medium">Status</th>
                            <th class="pb-3 text-neutral-300 font-medium">Dibuat</th>
                            <th class="pb-3 text-neutral-300 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($ingredients as $ingredient)
                        <tr class="border-b border-neutral-700/50 hover:bg-neutral-700/30">
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $ingredient->name }}</p>
                                    @if($ingredient->name_japanese)
                                    <p class="text-sm text-blue-400">{{ $ingredient->name_japanese }}</p>
                                    @endif
                                    @if($ingredient->description)
                                    <p class="text-sm text-neutral-400 mt-1">{{ Str::limit($ingredient->description, 50) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    @php
                                        $typeIcons = [
                                            'fresh' => ['fas fa-leaf', 'text-green-400'],
                                            'dry' => ['fas fa-box', 'text-yellow-400'],
                                            'frozen' => ['fas fa-snowflake', 'text-blue-400'],
                                            'liquid' => ['fas fa-tint', 'text-blue-300']
                                        ];
                                        $icon = $typeIcons[$ingredient->type] ?? ['fas fa-question', 'text-neutral-400'];
                                    @endphp
                                    <i class="{{ $icon[0] }} {{ $icon[1] }}"></i>
                                    <span class="text-sm text-neutral-300">{{ $types[$ingredient->type] ?? $ingredient->type }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-semibold text-white">{{ number_format($ingredient->current_stock, 2) }}</span>
                                        <span class="text-sm text-neutral-400">{{ $ingredient->unit }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs">
                                        <span class="text-neutral-400">Min: {{ number_format($ingredient->minimum_stock, 2) }}</span>
                                        <span class="text-neutral-600">|</span>
                                        <span class="text-neutral-400">Max: {{ number_format($ingredient->maximum_stock, 2) }}</span>
                                    </div>
                                    @if($ingredient->isLowStock())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-600/20 text-red-400">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Stok Rendah
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-white">Rp {{ number_format($ingredient->cost_per_unit, 0, ',', '.') }}</p>
                                    <p class="text-neutral-400">per {{ $ingredient->unit }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                @if($ingredient->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-600/20 text-green-400">
                                    <i class="fas fa-check mr-2"></i>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600/20 text-red-400">
                                    <i class="fas fa-times mr-2"></i>
                                    Nonaktif
                                </span>
                                @endif
                            </td>
                            <td class="py-4">
                                <p class="text-sm text-neutral-300">{{ $ingredient->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-neutral-400">{{ $ingredient->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    @if(Auth::user()->role->hasPermission('view_ingredients'))
                                    <a href="{{ route('ingredients.show', $ingredient) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('edit_ingredients'))
                                    <a href="{{ route('ingredients.edit', $ingredient) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('delete_ingredients'))
                                    <form method="POST" action="{{ route('ingredients.destroy', $ingredient) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-300" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus bahan baku ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between mt-6">
                <div class="text-sm text-neutral-400">
                    Menampilkan {{ $ingredients->firstItem() ?? 0 }} sampai {{ $ingredients->lastItem() ?? 0 }} 
                    dari {{ $ingredients->total() }} bahan baku
                </div>
                <div class="flex items-center space-x-2">
                    {{ $ingredients->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-seedling text-6xl text-neutral-600 mb-4"></i>
                <p class="text-xl text-neutral-400 mb-4">Belum ada bahan baku</p>
                <p class="text-neutral-500 mb-6">Tambah bahan baku pertama untuk memulai manajemen inventory</p>
                @if(Auth::user()->role->hasPermission('create_ingredients'))
                <a href="{{ route('ingredients.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Bahan Baku Pertama</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Stats --}}
    @if($ingredients->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-600/20 rounded-lg">
                    <i class="fas fa-seedling text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Total Bahan Baku</p>
                    <p class="text-2xl font-bold text-white">{{ $ingredients->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-600/20 rounded-lg">
                    <i class="fas fa-check-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Bahan Aktif</p>
                    <p class="text-2xl font-bold text-white">{{ $ingredients->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-red-600/20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Stok Rendah</p>
                    <p class="text-2xl font-bold text-white">{{ $ingredients->filter(function($ing) { return $ing->isLowStock(); })->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-600/20 rounded-lg">
                    <i class="fas fa-dollar-sign text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Nilai Inventory</p>
                    <p class="text-2xl font-bold text-white">
                        Rp {{ number_format($ingredients->sum(function($ing) { return $ing->current_stock * $ing->cost_per_unit; }), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
