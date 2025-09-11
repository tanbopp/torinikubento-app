@extends('partials.layouts.main')

@section('page-title', 'Detail Bahan Baku')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🥬 Detail Bahan Baku</h1>
                <p class="text-neutral-400">Informasi lengkap bahan baku {{ $ingredient->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('edit_ingredients'))
                <a href="{{ route('ingredients.edit', $ingredient) }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit</span>
                </a>
                @endif
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Info --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi Dasar</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-400 mb-2">Nama Bahan</label>
                            <p class="text-white text-lg font-semibold">{{ $ingredient->name }}</p>
                        </div>
                        @if($ingredient->name_japanese)
                        <div>
                            <label class="block text-sm font-medium text-neutral-400 mb-2">Nama Jepang</label>
                            <p class="text-blue-400 text-lg">{{ $ingredient->name_japanese }}</p>
                        </div>
                        @endif
                    </div>
                    
                    @if($ingredient->description)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Deskripsi</label>
                        <p class="text-neutral-300">{{ $ingredient->description }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-400 mb-2">Jenis</label>
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
                                <span class="text-white">{{ App\Models\Ingredient::getTypes()[$ingredient->type] ?? $ingredient->type }}</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-400 mb-2">Satuan</label>
                            <p class="text-white">{{ App\Models\Ingredient::getUnits()[$ingredient->unit] ?? $ingredient->unit }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-400 mb-2">Harga per Satuan</label>
                            <p class="text-white text-lg font-semibold">Rp {{ number_format($ingredient->cost_per_unit, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stock Information --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi Stok</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="p-4 bg-neutral-700 rounded-lg">
                                <p class="text-sm text-neutral-400 mb-2">Stok Saat Ini</p>
                                <p class="text-2xl font-bold text-white">{{ number_format($ingredient->current_stock, 2) }}</p>
                                <p class="text-sm text-neutral-400">{{ $ingredient->unit }}</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="p-4 bg-neutral-700 rounded-lg">
                                <p class="text-sm text-neutral-400 mb-2">Stok Minimum</p>
                                <p class="text-xl font-semibold text-yellow-400">{{ number_format($ingredient->minimum_stock, 2) }}</p>
                                <p class="text-sm text-neutral-400">{{ $ingredient->unit }}</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="p-4 bg-neutral-700 rounded-lg">
                                <p class="text-sm text-neutral-400 mb-2">Stok Maksimum</p>
                                <p class="text-xl font-semibold text-green-400">{{ number_format($ingredient->maximum_stock, 2) }}</p>
                                <p class="text-sm text-neutral-400">{{ $ingredient->unit }}</p>
                            </div>
                        </div>
                    </div>

                    @if($ingredient->isLowStock())
                    <div class="mt-6 p-4 bg-red-600/20 border border-red-600/30 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-400 mr-3"></i>
                            <div>
                                <p class="text-red-400 font-semibold">Stok Rendah!</p>
                                <p class="text-red-300 text-sm">Stok saat ini berada di bawah batas minimum.</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mt-6">
                        <p class="text-sm text-neutral-400 mb-2">Nilai Stok Saat Ini</p>
                        <p class="text-xl font-bold text-orange-400">
                            Rp {{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Additional Information --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi Tambahan</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($ingredient->shelf_life_days)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Masa Simpan</label>
                        <p class="text-white">{{ $ingredient->shelf_life_days }} hari</p>
                    </div>
                    @endif

                    @if($ingredient->storage_instruction)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Instruksi Penyimpanan</label>
                        <p class="text-neutral-300">{{ $ingredient->storage_instruction }}</p>
                    </div>
                    @endif

                    @if($ingredient->supplier_info && count($ingredient->supplier_info) > 0)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Informasi Supplier</label>
                        <div class="bg-neutral-700 rounded p-3">
                            <pre class="text-neutral-300 text-sm">{{ json_encode($ingredient->supplier_info, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif

                    @if($ingredient->allergen_info && count($ingredient->allergen_info) > 0)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Informasi Alergen</label>
                        <div class="bg-neutral-700 rounded p-3">
                            <pre class="text-neutral-300 text-sm">{{ json_encode($ingredient->allergen_info, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif

                    @if($ingredient->nutritional_value && count($ingredient->nutritional_value) > 0)
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-2">Nilai Nutrisi</label>
                        <div class="bg-neutral-700 rounded p-3">
                            <pre class="text-neutral-300 text-sm">{{ json_encode($ingredient->nutritional_value, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Used in Products --}}
            @if($ingredient->products->count() > 0)
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Digunakan dalam Produk</h3>
                    <p class="text-sm text-neutral-400 mt-1">{{ $ingredient->products->count() }} produk menggunakan bahan ini</p>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($ingredient->products as $product)
                        <div class="bg-neutral-700 rounded-lg p-4 border border-neutral-600">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-neutral-600 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-utensils text-neutral-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="text-white hover:text-orange-400 font-medium transition-colors">
                                                {{ $product->name }}
                                            </a>
                                            @if($product->name_japanese)
                                                <p class="text-sm text-neutral-400">{{ $product->name_japanese }}</p>
                                            @endif
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="text-xs bg-neutral-600 text-neutral-300 px-2 py-1 rounded">
                                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                                </span>
                                                @if($product->pivot->is_optional)
                                                    <span class="text-xs bg-yellow-600/20 text-yellow-400 px-2 py-1 rounded">
                                                        Opsional
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-neutral-300">
                                        <span class="font-medium">{{ number_format($product->pivot->quantity, 2) }} {{ $product->pivot->unit ?? $ingredient->unit }}</span>
                                    </div>
                                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                    <div class="text-xs text-neutral-400">
                                        Biaya: Rp {{ number_format($product->pivot->quantity * $ingredient->cost_per_unit, 0, ',', '.') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total Usage Analysis --}}
                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                    <div class="mt-6 p-4 bg-blue-600/10 border border-blue-600/20 rounded-lg">
                        <h4 class="text-sm font-semibold text-blue-400 mb-3">📊 Analisis Penggunaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-xs text-blue-300">Total Kebutuhan per Batch:</span>
                                <div class="text-lg font-bold text-blue-400">
                                    {{ number_format($ingredient->products->sum('pivot.quantity'), 2) }} {{ $ingredient->unit }}
                                </div>
                            </div>
                            <div>
                                <span class="text-xs text-blue-300">Nilai Total per Batch:</span>
                                <div class="text-lg font-bold text-blue-400">
                                    Rp {{ number_format($ingredient->products->sum(function($product) use ($ingredient) { 
                                        return $product->pivot->quantity * $ingredient->cost_per_unit; 
                                    }), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Digunakan dalam Produk</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <i class="fas fa-utensils text-neutral-500 text-3xl mb-4"></i>
                        <p class="text-neutral-400">Bahan ini belum digunakan dalam produk apapun</p>
                        <p class="text-sm text-neutral-500 mt-2">Tambahkan ke resep produk untuk mulai tracking penggunaan</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Status --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Status</h3>
                </div>
                <div class="p-6">
                    @if($ingredient->is_active)
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-green-600/20 text-green-400">
                        <i class="fas fa-check mr-2"></i>
                        Aktif
                    </span>
                    @else
                    <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-red-600/20 text-red-400">
                        <i class="fas fa-times mr-2"></i>
                        Nonaktif
                    </span>
                    @endif
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi Waktu</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-1">Dibuat</label>
                        <p class="text-white">{{ $ingredient->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-neutral-400">{{ $ingredient->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-400 mb-1">Terakhir Diupdate</label>
                        <p class="text-white">{{ $ingredient->updated_at->format('d M Y, H:i') }}</p>
                        <p class="text-xs text-neutral-400">{{ $ingredient->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Aksi</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if(Auth::user()->role->hasPermission('edit_ingredients'))
                    <a href="{{ route('ingredients.edit', $ingredient) }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit Bahan Baku</span>
                    </a>
                    @endif
                    
                    @if(Auth::user()->role->hasPermission('delete_ingredients'))
                    <form action="{{ route('ingredients.destroy', $ingredient) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan baku ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-trash"></i>
                            <span>Hapus Bahan Baku</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
