@extends('partials.layouts.main')

@section('page-title', 'Detail Produk')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍜 {{ $product->name }}</h1>
                @if($product->name_japanese)
                <p class="text-lg text-blue-400 mb-2">{{ $product->name_japanese }}</p>
                @endif
                <p class="text-neutral-400">Detail lengkap produk menu resto Jepang</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('edit_products'))
                <a href="{{ route('products.edit', $product) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit Produk</span>
                </a>
                @endif
                <a href="{{ route('products.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        {{-- Left Column - Main Info --}}
        <div class="xl:col-span-2 space-y-6">
            {{-- Product Image & Basic Info --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Image --}}
                        <div class="flex justify-center">
                            @if($product->image_path)
                            <div class="w-64 h-64 overflow-hidden rounded-lg border-2 border-neutral-600">
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            @else
                            <div class="w-64 h-64 bg-neutral-700 rounded-lg flex items-center justify-center border-2 border-neutral-600">
                                <i class="fas fa-utensils text-6xl text-neutral-400"></i>
                            </div>
                            @endif
                        </div>

                        {{-- Basic Details --}}
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold text-white">{{ $product->name }}</h3>
                                @if($product->name_japanese)
                                <p class="text-blue-400">{{ $product->name_japanese }}</p>
                                @endif
                            </div>

                            @if($product->description)
                            <div>
                                <h4 class="text-sm font-medium text-neutral-300 mb-2">Deskripsi</h4>
                                <p class="text-neutral-400">{{ $product->description }}</p>
                            </div>
                            @endif

                            {{-- Category & Spice Level --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-neutral-300 mb-2">Kategori</h4>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-neutral-700 text-neutral-300">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                
                                @if($product->spice_level > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-neutral-300 mb-2">Level Pedas</h4>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600/20 text-red-400">
                                        @for($i = 1; $i <= $product->spice_level; $i++)🌶️@endfor Level {{ $product->spice_level }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing Info --}}
            @if(Auth::user()->role->hasPermission('view_pricing'))
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi Harga</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Base Price --}}
                        <div class="text-center">
                            <div class="p-4 bg-neutral-700 rounded-lg">
                                <h4 class="text-sm text-neutral-300 mb-2">Harga Jual</h4>
                                <p class="text-2xl font-bold text-white">Rp {{ number_format($product->base_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Promo Price --}}
                        @if($product->promo_price && $product->promo_price < $product->base_price)
                        <div class="text-center">
                            <div class="p-4 bg-red-600/20 border border-red-600/30 rounded-lg">
                                <h4 class="text-sm text-red-300 mb-2">Harga Promo</h4>
                                <p class="text-2xl font-bold text-red-400">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-red-300 mt-1">
                                    Hemat Rp {{ number_format($product->base_price - $product->promo_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endif

                        {{-- Cost Analysis --}}
                        @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                        <div class="text-center">
                            <div class="p-4 bg-neutral-700 rounded-lg">
                                <h4 class="text-sm text-neutral-300 mb-2">Harga Pokok</h4>
                                <p class="text-lg font-bold text-white">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-{{ $product->margin_percentage > 30 ? 'green' : ($product->margin_percentage > 15 ? 'yellow' : 'red') }}-400 mt-1">
                                    {{ number_format($product->margin_percentage, 1) }}% margin
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- BOM (Bill of Materials) --}}
            @if(Auth::user()->role->hasPermission('view_bom') && $product->ingredients->count() > 0)
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Bill of Materials (BOM)</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left border-b border-neutral-700">
                                    <th class="pb-3 text-neutral-300 font-medium">Bahan</th>
                                    <th class="pb-3 text-neutral-300 font-medium">Jumlah</th>
                                    <th class="pb-3 text-neutral-300 font-medium">Satuan</th>
                                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                    <th class="pb-3 text-neutral-300 font-medium">Biaya per Unit</th>
                                    <th class="pb-3 text-neutral-300 font-medium">Total Biaya</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="text-white">
                                @foreach($product->ingredients as $ingredient)
                                <tr class="border-b border-neutral-700/50">
                                    <td class="py-3">{{ $ingredient->name }}</td>
                                    <td class="py-3">{{ $ingredient->pivot->quantity }}</td>
                                    <td class="py-3">{{ $ingredient->unit }}</td>
                                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                    <td class="py-3">Rp {{ number_format($ingredient->cost_per_unit, 0, ',', '.') }}</td>
                                    <td class="py-3">Rp {{ number_format($ingredient->pivot->quantity * $ingredient->cost_per_unit, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column - Status & Settings --}}
        <div class="space-y-6">
            {{-- Status Overview --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Status Aktif</span>
                        @if($product->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-600/20 text-green-400">
                            <i class="fas fa-check mr-2"></i> Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600/20 text-red-400">
                            <i class="fas fa-times mr-2"></i> Nonaktif
                        </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Ketersediaan</span>
                        @if($product->is_available)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600/20 text-blue-400">
                            <i class="fas fa-check-circle mr-2"></i> Tersedia
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-600/20 text-orange-400">
                            <i class="fas fa-exclamation-circle mr-2"></i> Habis
                        </span>
                        @endif
                    </div>

                    @if($product->is_seasonal)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Tipe</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-600/20 text-purple-400">
                            <i class="fas fa-seedling mr-2"></i> Musiman
                        </span>
                    </div>
                    @endif

                    @if($product->is_limited_edition)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Edition</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-600/20 text-yellow-400">
                            <i class="fas fa-star mr-2"></i> Limited
                        </span>
                    </div>
                    @endif

                    @if($product->daily_limit)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Batas Harian</span>
                        <span class="text-white">{{ $product->daily_limit }} porsi</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Product Variants --}}
            @if($product->variants->count() > 0)
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Varian Produk</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($product->variants as $variant)
                        <div class="flex items-center justify-between p-3 bg-neutral-700 rounded-lg">
                            <div>
                                <p class="font-medium text-white">{{ $variant->name }}</p>
                                <p class="text-sm text-neutral-400">{{ $variant->description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-white">+Rp {{ number_format($variant->additional_price, 0, ',', '.') }}</p>
                                @if(!$variant->is_available)
                                <p class="text-xs text-red-400">Tidak tersedia</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Product Info --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Urutan</span>
                        <span class="text-white">{{ $product->sort_order }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Dibuat</span>
                        <span class="text-white">{{ $product->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Terakhir Diubah</span>
                        <span class="text-white">{{ $product->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            @if(Auth::user()->role->hasPermission('edit_products'))
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <form method="POST" action="{{ route('products.toggle-status', $product) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-{{ $product->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $product->is_active ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-{{ $product->is_active ? 'times' : 'check' }} mr-2"></i>
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Produk
                        </button>
                    </form>

                    <form method="POST" action="{{ route('products.toggle-availability', $product) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-{{ $product->is_available ? 'orange' : 'blue' }}-600 hover:bg-{{ $product->is_available ? 'orange' : 'blue' }}-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-{{ $product->is_available ? 'times-circle' : 'check-circle' }} mr-2"></i>
                            {{ $product->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
