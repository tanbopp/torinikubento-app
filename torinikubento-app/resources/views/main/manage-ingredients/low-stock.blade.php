@extends('partials.layouts.main')

@section('page-title', 'Bahan Baku - Stok Rendah')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">⚠️ Bahan Baku Stok Rendah</h1>
                <p class="text-neutral-400">Daftar bahan baku yang stoknya di bawah batas minimum</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                <a href="{{ route('ingredients.near-expiry') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-clock text-sm"></i>
                    <span>Hampir Kadaluarsa</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if($ingredients->count() > 0)
    <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-3"></i>
            <div>
                <p class="font-semibold">Perhatian!</p>
                <p class="text-sm">Ditemukan {{ $ingredients->total() }} bahan baku dengan stok rendah. Segera lakukan restocking untuk mencegah kehabisan stok.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Low Stock Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Bahan Baku dengan Stok Rendah</h3>
        </div>
        <div class="p-6">
            @if($ingredients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-neutral-700">
                            <th class="pb-3 text-neutral-300 font-medium">Nama Bahan</th>
                            <th class="pb-3 text-neutral-300 font-medium">Jenis</th>
                            <th class="pb-3 text-neutral-300 font-medium">Stok Saat Ini</th>
                            <th class="pb-3 text-neutral-300 font-medium">Batas Minimum</th>
                            <th class="pb-3 text-neutral-300 font-medium">Selisih</th>
                            <th class="pb-3 text-neutral-300 font-medium">Harga/Unit</th>
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
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-600/20 text-red-400 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Stok Rendah
                                    </span>
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
                                    <span class="text-sm text-neutral-300">{{ App\Models\Ingredient::getTypes()[$ingredient->type] ?? $ingredient->type }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-red-400">{{ number_format($ingredient->current_stock, 2) }}</p>
                                    <p class="text-xs text-neutral-400">{{ $ingredient->unit }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-center">
                                    <p class="text-lg font-semibold text-yellow-400">{{ number_format($ingredient->minimum_stock, 2) }}</p>
                                    <p class="text-xs text-neutral-400">{{ $ingredient->unit }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-center">
                                    @php
                                        $difference = $ingredient->current_stock - $ingredient->minimum_stock;
                                        $percentage = $ingredient->minimum_stock > 0 ? ($ingredient->current_stock / $ingredient->minimum_stock) * 100 : 0;
                                    @endphp
                                    <p class="text-lg font-bold text-red-400">{{ number_format($difference, 2) }}</p>
                                    <p class="text-xs text-red-300">({{ number_format($percentage, 1) }}%)</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-white">Rp {{ number_format($ingredient->cost_per_unit, 0, ',', '.') }}</p>
                                    <p class="text-neutral-400">per {{ $ingredient->unit }}</p>
                                </div>
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
                    dari {{ $ingredients->total() }} bahan baku dengan stok rendah
                </div>
                <div class="flex items-center space-x-2">
                    {{ $ingredients->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-6xl text-green-600 mb-4"></i>
                <p class="text-xl text-neutral-400 mb-4">Semua stok dalam kondisi baik</p>
                <p class="text-neutral-500 mb-6">Tidak ada bahan baku yang stoknya di bawah batas minimum</p>
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Bahan Baku</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Summary Statistics --}}
    @if($ingredients->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-red-600/20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Total Stok Rendah</p>
                    <p class="text-2xl font-bold text-red-400">{{ $ingredients->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-600/20 rounded-lg">
                    <i class="fas fa-dollar-sign text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Nilai Stok Kritis</p>
                    <p class="text-2xl font-bold text-yellow-400">
                        Rp {{ number_format($ingredients->sum(function($ing) { return $ing->current_stock * $ing->cost_per_unit; }), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-600/20 rounded-lg">
                    <i class="fas fa-shopping-cart text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Est. Biaya Restock</p>
                    <p class="text-2xl font-bold text-orange-400">
                        Rp {{ number_format($ingredients->sum(function($ing) { return ($ing->maximum_stock - $ing->current_stock) * $ing->cost_per_unit; }), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-600/20 rounded-lg">
                    <i class="fas fa-percentage text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">% dari Total Bahan</p>
                    <p class="text-2xl font-bold text-blue-400">
                        {{ $ingredients->total() > 0 ? number_format(($ingredients->total() / App\Models\Ingredient::count()) * 100, 1) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
