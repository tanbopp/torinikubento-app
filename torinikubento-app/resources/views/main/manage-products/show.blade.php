@extends('partials.layouts.main')

@section('page-title', 'Detail Produk')

@section('main-content')
<div class="p-6">
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

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-end justify-between mt-6">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $product->name }}</h1>
                @if($product->name_japanese)
                <p class="text-lg text-blue-400 mb-2">{{ $product->name_japanese }}</p>
                @endif
                <p class="text-neutral-400">Detail lengkap produk menu resto Jepang</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('edit_products'))
                <a href="{{ route('products.edit', $product) }}" class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit Produk</span>
                </a>
                @endif
                <a href="{{ route('products.index') }}" class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Left: Product Image & Basic Info (Sticky) --}}
        <div class="lg:col-span-1">
            <div class="sticky top-4 space-y-3">
                {{-- Product Image --}}
                <div class="aspect-square rounded-lg overflow-hidden bg-neutral-800/20">
                    @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-neutral-800 flex items-center justify-center">
                        <i class="fas fa-utensils text-4xl text-neutral-500"></i>
                    </div>
                    @endif
                </div>

                {{-- Quick Info --}}
                <div class="p-3 rounded bg-neutral-800/30">
                    <h4 class="text-sm font-semibold text-white mb-2">Info Cepat</h4>
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Kategori:</span>
                            <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-neutral-700 text-neutral-300">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        @if($product->spice_level > 0)
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Level Pedas:</span>
                            <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-red-600/20 text-red-400">
                                🌶️ Level {{ $product->spice_level }}
                            </span>
                        </div>
                        @endif

                        @if($product->preparation_time)
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Waktu Masak:</span>
                            <span class="text-white">{{ $product->preparation_time }}m</span>
                        </div>
                        @endif

                        @if($product->daily_limit)
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Batas Harian:</span>
                            <span class="text-white">{{ $product->daily_limit }} porsi</span>
                        </div>
                        @endif

                        @if($product->popularity_score)
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Popularitas:</span>
                            <div class="flex items-center space-x-1">
                                <span class="text-white">{{ $product->popularity_score }}/100</span>
                                <div class="w-8 bg-neutral-700 rounded-full h-1">
                                    <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $product->popularity_score }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Status Badges --}}
                <div class="p-3 rounded bg-neutral-800/30">
                    <h4 class="text-sm font-semibold text-white mb-2">Status</h4>
                    <div class="flex flex-wrap gap-1.5">
                        @if($product->is_active)
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-green-600/20 text-green-400">
                            <i class="fas fa-check mr-1"></i> Aktif
                        </span>
                        @else
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-red-600/20 text-red-400">
                            <i class="fas fa-times mr-1"></i> Nonaktif
                        </span>
                        @endif

                        @if($product->is_available)
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-blue-600/20 text-blue-400">
                            <i class="fas fa-check-circle mr-1"></i> Tersedia
                        </span>
                        @else
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-orange-600/20 text-orange-400">
                            <i class="fas fa-exclamation-circle mr-1"></i> Habis
                        </span>
                        @endif

                        @if($product->is_seasonal)
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-purple-600/20 text-purple-400">
                            <i class="fas fa-seedling mr-1"></i> Musiman
                        </span>
                        @endif

                        @if($product->is_limited_edition)
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-600/20 text-yellow-400">
                            <i class="fas fa-star mr-1"></i> Limited
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Quick Actions --}}
                @if(Auth::user()->role->hasPermission('edit_products'))
                <div class="space-y-1.5">
                    <form method="POST" action="{{ route('products.toggle-status', $product) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full hover:bg-{{ $product->is_active ? 'red' : 'green' }}-700/50 text-{{ $product->is_active ? 'red' : 'green' }}-100 font-medium px-2 py-1.5 border border-{{ $product->is_active ? 'red' : 'green' }}-600/30 active:bg-{{ $product->is_active ? 'red' : 'green' }}-700 rounded-lg transition-colors text-xs"
                                onclick="return confirm('{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk {{ $product->name }}?')">
                            <i class="fas fa-{{ $product->is_active ? 'times' : 'check' }} mr-1"></i>
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('products.toggle-availability', $product) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full hover:bg-{{ $product->is_available ? 'orange' : 'blue' }}-700/50 text-{{ $product->is_available ? 'orange' : 'blue' }}-100 font-medium px-2 py-1.5 border border-{{ $product->is_available ? 'orange' : 'blue' }}-600/30 active:bg-{{ $product->is_available ? 'orange' : 'blue' }}-700 rounded-lg transition-colors text-xs"
                                onclick="return confirm('{{ $product->is_available ? 'Tandai habis' : 'Tandai tersedia' }} untuk produk {{ $product->name }}?')">
                            <i class="fas fa-{{ $product->is_available ? 'times-circle' : 'check-circle' }} mr-1"></i>
                            {{ $product->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Center: Main Content --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Basic Info --}}
            <div class="p-4 rounded bg-neutral-800/20">
                @if($product->description)
                <div class="mb-3">
                    <h3 class="text-base font-semibold text-white mb-2">Deskripsi</h3>
                    <p class="text-neutral-400 text-sm leading-relaxed">{{ $product->description }}</p>
                </div>
                @endif

                {{-- Allergen Info --}}
                @if($product->allergen_info && is_array($product->allergen_info) && count($product->allergen_info) > 0)
                <div class="mb-3">
                    <h4 class="text-sm font-semibold text-white mb-2">Informasi Alergen</h4>
                    <div class="flex flex-wrap gap-1">
                        @foreach($product->allergen_info as $allergen)
                        <span class="px-1.5 py-0.5 rounded text-xs font-medium bg-orange-600/20 text-orange-400 border border-orange-600/30">
                            {{ ucfirst($allergen) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Seasonal Info --}}
                @if($product->is_seasonal && $product->season_start_date && $product->season_end_date)
                <div>
                    <h4 class="text-sm font-semibold text-white mb-2">Periode Musiman</h4>
                    <p class="text-neutral-400 text-sm">
                        <i class="fas fa-calendar mr-2"></i>
                        {{ $product->season_start_date->format('d M') }} - {{ $product->season_end_date->format('d M Y') }}
                    </p>
                </div>
                @endif
            </div>

            {{-- Bill of Materials (BOM) --}}
            @if(Auth::user()->role->hasPermission('view_bom') && $product->ingredients->count() > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-white">Bill of Materials</h3>
                    <span class="text-xs text-neutral-400">{{ $product->ingredients->count() }} bahan</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-left border-b border-neutral-700">
                                <th class="pb-2 text-neutral-300 font-medium">Bahan</th>
                                <th class="pb-2 text-neutral-300 font-medium text-right">Qty</th>
                                <th class="pb-2 text-neutral-300 font-medium">Unit</th>
                                @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                <th class="pb-2 text-neutral-300 font-medium text-right">Harga/Unit</th>
                                <th class="pb-2 text-neutral-300 font-medium text-right">Total</th>
                                <th class="pb-2 text-neutral-300 font-medium text-center">Stock</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-700/30">
                            @php $totalIngredientCost = 0; @endphp
                            @foreach($product->ingredients as $ingredient)
                            @php
                                $costPerUnit = $ingredient->pivot->cost_per_unit ?? $ingredient->cost_per_unit;
                                $totalCost = $ingredient->pivot->quantity * $costPerUnit;
                                $totalIngredientCost += $totalCost;
                                
                                // Stock status
                                $stockStatus = 'aman';
                                $stockClass = 'text-green-400 bg-green-600/20';
                                if ($ingredient->current_stock <= $ingredient->minimum_stock) {
                                    $stockStatus = 'habis';
                                    $stockClass = 'text-red-400 bg-red-600/20';
                                } elseif ($ingredient->current_stock <= ($ingredient->minimum_stock * 1.5)) {
                                    $stockStatus = 'rendah';
                                    $stockClass = 'text-yellow-400 bg-yellow-600/20';
                                }
                            @endphp
                            <tr class="hover:bg-neutral-700/20">
                                <td class="py-1.5 text-white">
                                    <div>
                                        <p class="font-medium">{{ $ingredient->name }}</p>
                                        @if($ingredient->name_japanese)
                                        <p class="text-xs text-neutral-400">{{ $ingredient->name_japanese }}</p>
                                        @endif
                                        @if($ingredient->type)
                                        <span class="px-1 py-0.5 rounded text-xs bg-neutral-800 text-neutral-400 mt-0.5 inline-block">
                                            {{ ucfirst($ingredient->type) }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-1.5 text-white text-right font-mono">{{ number_format($ingredient->pivot->quantity, 1) }}</td>
                                <td class="py-1.5 text-white">{{ $ingredient->pivot->unit ?? $ingredient->unit }}</td>
                                @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                <td class="py-1.5 text-white text-right font-mono">Rp {{ number_format($costPerUnit, 0, ',', '.') }}</td>
                                <td class="py-1.5 text-white text-right font-mono font-semibold">Rp {{ number_format($totalCost, 0, ',', '.') }}</td>
                                <td class="py-1.5 text-center">
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ $stockClass }}">
                                        {{ ucfirst($stockStatus) }}
                                    </span>
                                    <p class="text-xs text-neutral-400 mt-0.5">{{ number_format($ingredient->current_stock, 1) }} {{ $ingredient->unit }}</p>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                        @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                        <tfoot class="border-t border-neutral-600">
                            <tr class="text-xs">
                                <td colspan="{{ Auth::user()->role->hasPermission('view_cost_analysis') ? '4' : '3' }}" class="py-2 text-right text-neutral-300 font-medium">
                                    <strong>Total Biaya Bahan:</strong>
                                </td>
                                <td class="py-2 text-white font-bold text-right">
                                    <strong>Rp {{ number_format($totalIngredientCost, 0, ',', '.') }}</strong>
                                </td>
                                <td class="py-2"></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
                
                {{-- BOM Summary --}}
                @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                <div class="mt-3 p-3 rounded bg-neutral-800/30">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                        <div class="text-center">
                            <p class="text-neutral-400">Total Bahan</p>
                            <p class="text-white font-semibold">{{ $product->ingredients->count() }} item</p>
                        </div>
                        <div class="text-center">
                            <p class="text-neutral-400">Biaya Bahan</p>
                            <p class="text-white font-semibold">Rp {{ number_format($totalIngredientCost, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-neutral-400">Biaya Lain</p>
                            <p class="text-white font-semibold">Rp {{ number_format($product->calculateOtherCosts(), 0, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-neutral-400">Total HPP</p>
                            <p class="text-white font-bold">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- Nutritional Information --}}
            @if($product->nutritional_info && is_array($product->nutritional_info) && count($product->nutritional_info) > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <h3 class="text-base font-semibold text-white mb-3">Informasi Nutrisi per Porsi</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach($product->nutritional_info as $key => $value)
                    <div class="text-center p-2 rounded bg-neutral-700/30">
                        <h4 class="text-xs text-neutral-400 mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</h4>
                        <p class="text-sm font-semibold text-white">{{ $value }}{{ in_array($key, ['calories']) ? ' kcal' : 'g' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Product Variants --}}
            @if($product->variants->count() > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-white">Varian Produk</h3>
                    <span class="text-xs text-neutral-400">{{ $product->variants->count() }} varian</span>
                </div>
                
                <div class="space-y-2">
                    @foreach($product->variants as $variant)
                    <div class="flex items-center justify-between p-2 rounded bg-neutral-700/30 hover:bg-neutral-700/50 transition-colors">
                        <div>
                            <p class="font-medium text-white text-sm">{{ $variant->name }}</p>
                            @if($variant->name_japanese)
                            <p class="text-xs text-neutral-400">{{ $variant->name_japanese }}</p>
                            @endif
                            @if($variant->description)
                            <p class="text-xs text-neutral-400">{{ $variant->description }}</p>
                            @endif
                            @if($variant->type)
                            <span class="px-1 py-0.5 rounded text-xs font-medium bg-neutral-800 text-neutral-300 mt-1 inline-block">
                                {{ ucfirst($variant->type) }}
                            </span>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($variant->price_adjustment != 0)
                            <p class="font-semibold text-white text-sm">
                                @if($variant->price_adjustment > 0)+@endif
                                Rp {{ number_format($variant->price_adjustment, 0, ',', '.') }}
                            </p>
                            @else
                            <p class="text-sm text-neutral-400">Standar</p>
                            @endif
                            @if(!$variant->is_active)
                            <p class="text-xs text-red-400">Tidak tersedia</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Product Addons --}}
            @if($product->addons->count() > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-white">Add-ons</h3>
                    <span class="text-xs text-neutral-400">{{ $product->addons->count() }} add-on</span>
                </div>
                
                <div class="space-y-2">
                    @foreach($product->addons as $addon)
                    <div class="flex items-center justify-between p-2 rounded bg-neutral-700/30 hover:bg-neutral-700/50 transition-colors">
                        <div>
                            <p class="font-medium text-white text-sm">{{ $addon->name }}</p>
                            @if($addon->name_japanese)
                            <p class="text-xs text-neutral-400">{{ $addon->name_japanese }}</p>
                            @endif
                            @if($addon->description)
                            <p class="text-xs text-neutral-400">{{ $addon->description }}</p>
                            @endif
                            @if($addon->category)
                            <span class="px-1 py-0.5 rounded text-xs font-medium bg-neutral-800 text-neutral-300 mt-1 inline-block">
                                {{ ucfirst($addon->category) }}
                            </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-white text-sm">
                                +Rp {{ number_format($addon->pivot->price ?? $addon->base_price, 0, ',', '.') }}
                            </p>
                            @if($addon->pivot->is_default)
                            <p class="text-xs text-blue-400">Default</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Bundle Information --}}
            @if($product->bundleItems->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Item dalam Bundle</h3>
                    <span class="text-sm text-neutral-400">{{ $product->bundleItems->count() }} item</span>
                </div>
                
                <div class="space-y-2">
                    @foreach($product->bundleItems as $item)
                    <div class="flex items-center justify-between p-3 rounded border border-neutral-700 hover:border-neutral-600 transition-colors">
                        <div class="flex items-center space-x-3">
                            @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" 
                                 class="w-8 h-8 rounded object-cover">
                            @else
                            <div class="w-8 h-8 bg-neutral-700 rounded flex items-center justify-center">
                                <i class="fas fa-utensils text-xs text-neutral-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-white text-sm">{{ $item->name }}</p>
                                @if($item->name_japanese)
                                <p class="text-xs text-neutral-400">{{ $item->name_japanese }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-neutral-300">{{ $item->pivot->quantity }}x</span>
                            @if($item->pivot->is_required)
                            <p class="text-xs text-blue-400">Wajib</p>
                            @else
                            <p class="text-xs text-neutral-400">Opsional</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($product->bundles->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Tersedia dalam Bundle</h3>
                    <span class="text-sm text-neutral-400">{{ $product->bundles->count() }} bundle</span>
                </div>
                
                <div class="space-y-2">
                    @foreach($product->bundles as $bundle)
                    <div class="flex items-center justify-between p-3 rounded border border-neutral-700 hover:border-neutral-600 transition-colors">
                        <div class="flex items-center space-x-3">
                            @if($bundle->image_path)
                            <img src="{{ asset('storage/' . $bundle->image_path) }}" 
                                 class="w-8 h-8 rounded object-cover">
                            @else
                            <div class="w-8 h-8 bg-neutral-700 rounded flex items-center justify-center">
                                <i class="fas fa-box text-xs text-neutral-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-white text-sm">{{ $bundle->name }}</p>
                                @if($bundle->name_japanese)
                                <p class="text-xs text-neutral-400">{{ $bundle->name_japanese }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm text-white">Rp {{ number_format($bundle->base_price, 0, ',', '.') }}</span>
                            <p class="text-xs text-neutral-400">{{ $bundle->pivot->quantity }}x</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        {{-- Right: Pricing & Additional Info --}}
        <div class="space-y-4">
            {{-- Pricing Info --}}
            @if(Auth::user()->role->hasPermission('view_pricing'))
            <div class="p-4 rounded bg-neutral-800/20">
                <h3 class="text-base font-semibold text-white mb-3">Informasi Harga</h3>
                <div class="space-y-2">
                    <div class="p-3 rounded bg-neutral-700/30">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-400">Harga Jual</span>
                            <span class="text-base font-bold text-white">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                        </div>
                        @if($product->promo_price && $product->promo_price < $product->base_price)
                        <div class="mt-2 pt-2 border-t border-neutral-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-red-400">Harga Promo</span>
                                <span class="text-base font-bold text-red-400">Rp {{ number_format($product->promo_price, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-xs text-red-400 text-right mt-1">
                                Hemat Rp {{ number_format($product->base_price - $product->promo_price, 0, ',', '.') }}
                            </p>
                        </div>
                        @endif
                    </div>

                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                    <div class="p-3 rounded bg-neutral-700/30">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-400">Harga Pokok</span>
                            <span class="text-base font-bold text-white">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-neutral-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-neutral-400">Margin</span>
                                @php
                                    $marginClass = $product->margin_percentage > 30 ? 'text-green-400' : 
                                                  ($product->margin_percentage > 15 ? 'text-yellow-400' : 'text-red-400');
                                @endphp
                                <span class="text-sm font-semibold {{ $marginClass }}">
                                    {{ number_format($product->margin_percentage, 1) }}%
                                </span>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-sm text-neutral-400">Laba</span>
                                <span class="text-sm text-white">
                                    Rp {{ number_format($product->base_price - $product->cost_price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Effective Price --}}
                    <div class="p-2 rounded border border-green-600/30 bg-green-600/10">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-green-400">Harga Efektif</span>
                            <span class="text-base font-bold text-green-400">
                                Rp {{ number_format($product->promo_price && $product->promo_price < $product->base_price ? $product->promo_price : $product->base_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tax & Other Costs --}}
            @if(Auth::user()->role->hasPermission('view_cost_analysis') && $product->otherCosts->count() > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <h3 class="text-base font-semibold text-white mb-3">Komponen Biaya Tambahan</h3>
                <div class="space-y-1.5">
                    @php $totalOtherCosts = 0; @endphp
                    @foreach($product->otherCosts as $otherCost)
                    @php
                        $costValue = $otherCost->pivot->custom_value ?? $otherCost->value;
                        if($otherCost->type === 'percentage') {
                            $actualCost = $product->calculateFoodCost() * ($costValue / 100);
                        } else {
                            $actualCost = $costValue;
                        }
                        $totalOtherCosts += $actualCost;
                    @endphp
                    <div class="flex justify-between items-center p-2 rounded bg-neutral-700/30">
                        <div>
                            <p class="text-sm font-medium text-white">{{ $otherCost->name }}</p>
                            <p class="text-xs text-neutral-400">{{ $otherCost->description ?? ucfirst($otherCost->type) }}</p>
                        </div>
                        <div class="text-right">
                            @if($otherCost->type === 'percentage')
                            <span class="text-sm text-white">{{ number_format($costValue, 1) }}%</span>
                            <p class="text-xs text-neutral-400">dari Rp {{ number_format($product->calculateFoodCost(), 0, ',', '.') }}</p>
                            <p class="text-sm font-semibold text-white">= Rp {{ number_format($actualCost, 0, ',', '.') }}</p>
                            @else
                            <span class="text-sm font-semibold text-white">Rp {{ number_format($actualCost, 0, ',', '.') }}</span>
                            <p class="text-xs text-neutral-400">Fixed cost</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    {{-- Other Costs Summary --}}
                    <div class="mt-2 p-2 rounded bg-neutral-800/50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-neutral-300">Total Biaya Tambahan:</span>
                            <span class="text-sm font-bold text-white">Rp {{ number_format($totalOtherCosts, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Stock & Ingredient Info --}}
            @if(Auth::user()->role->hasPermission('view_bom') && $product->ingredients->count() > 0)
            <div class="p-4 rounded bg-neutral-800/20">
                <h3 class="text-base font-semibold text-white mb-3">Detail Stok Bahan Baku</h3>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach($product->ingredients->take(5) as $ingredient)
                    @php
                        $stockStatus = 'Aman';
                        $stockClass = 'text-green-400 bg-green-600/20';
                        $stockIcon = 'fa-check-circle';
                        
                        if ($ingredient->current_stock <= $ingredient->minimum_stock) {
                            $stockStatus = 'Stok Habis';
                            $stockClass = 'text-red-400 bg-red-600/20';
                            $stockIcon = 'fa-exclamation-triangle';
                        } elseif ($ingredient->current_stock <= ($ingredient->minimum_stock * 1.5)) {
                            $stockStatus = 'Stok Rendah';
                            $stockClass = 'text-yellow-400 bg-yellow-600/20';
                            $stockIcon = 'fa-exclamation-circle';
                        }
                        
                        $usageForProduct = $ingredient->pivot->quantity;
                        $availablePortions = $ingredient->current_stock > 0 ? floor($ingredient->current_stock / $usageForProduct) : 0;
                        $costPerUnit = $ingredient->pivot->cost_per_unit ?? $ingredient->cost_per_unit;
                    @endphp
                    <div class="p-2 rounded bg-neutral-700/30 hover:bg-neutral-700/50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <p class="font-medium text-white text-sm">{{ $ingredient->name }}</p>
                                    <span class="px-1.5 py-0.5 rounded text-xs font-medium {{ $stockClass }}">
                                        <i class="fas {{ $stockIcon }}"></i>
                                    </span>
                                </div>
                                @if($ingredient->name_japanese)
                                <p class="text-xs text-neutral-400 mt-1">{{ $ingredient->name_japanese }}</p>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-2 mt-1 text-xs">
                                    <div>
                                        <span class="text-neutral-400">Kebutuhan:</span>
                                        <p class="text-white font-mono">{{ number_format($usageForProduct, 1) }} {{ $ingredient->pivot->unit ?? $ingredient->unit }}</p>
                                    </div>
                                    <div>
                                        <span class="text-neutral-400">Stok:</span>
                                        <p class="text-white font-mono">{{ number_format($ingredient->current_stock, 1) }} {{ $ingredient->unit }}</p>
                                    </div>
                                    <div>
                                        <span class="text-neutral-400">Bisa buat:</span>
                                        <p class="text-white font-semibold">{{ $availablePortions }} porsi</p>
                                    </div>
                                    @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                                    <div>
                                        <span class="text-neutral-400">Biaya:</span>
                                        <p class="text-white font-mono">Rp {{ number_format($usageForProduct * $costPerUnit, 0, ',', '.') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($product->ingredients->count() > 5)
                    <p class="text-xs text-neutral-400 text-center py-2">+{{ $product->ingredients->count() - 5 }} bahan lainnya</p>
                    @endif
                    
                    {{-- Stock Summary --}}
                    @php
                        $lowStockCount = $product->ingredients->filter(function($ingredient) {
                            return $ingredient->current_stock <= $ingredient->minimum_stock;
                        })->count();
                        
                        $warningStockCount = $product->ingredients->filter(function($ingredient) {
                            return $ingredient->current_stock > $ingredient->minimum_stock && 
                                   $ingredient->current_stock <= ($ingredient->minimum_stock * 1.5);
                        })->count();
                        
                        // Calculate minimum portions possible
                        $minPortions = $product->ingredients->min(function($ingredient) {
                            $usageForProduct = $ingredient->pivot->quantity;
                            return $ingredient->current_stock > 0 ? floor($ingredient->current_stock / $usageForProduct) : 0;
                        });
                    @endphp
                    
                    <div class="mt-3 p-3 rounded bg-neutral-800/30">
                        <h4 class="text-sm font-semibold text-white mb-2">Ringkasan Stok</h4>
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="text-center">
                                <p class="text-neutral-400">Max Porsi</p>
                                <p class="text-white font-bold text-base">{{ $minPortions }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-neutral-400">Bahan Aman</p>
                                <p class="text-green-400 font-semibold">{{ $product->ingredients->count() - $lowStockCount - $warningStockCount }}</p>
                            </div>
                            @if($warningStockCount > 0)
                            <div class="text-center">
                                <p class="text-neutral-400">Perlu Perhatian</p>
                                <p class="text-yellow-400 font-semibold">{{ $warningStockCount }}</p>
                            </div>
                            @endif
                            @if($lowStockCount > 0)
                            <div class="text-center">
                                <p class="text-neutral-400">Stok Habis</p>
                                <p class="text-red-400 font-semibold">{{ $lowStockCount }}</p>
                            </div>
                            @endif
                        </div>
                        
                        @if($lowStockCount > 0 || $warningStockCount > 0)
                        <div class="mt-2 pt-2 border-t border-neutral-700">
                            <p class="text-xs text-orange-400 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                @if($lowStockCount > 0)
                                    Ada {{ $lowStockCount }} bahan stok habis!
                                @elseif($warningStockCount > 0)
                                    Ada {{ $warningStockCount }} bahan stok rendah.
                                @endif
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Analysis & Reports --}}
            <div class="p-4 rounded bg-neutral-800/20">
                <h3 class="text-base font-semibold text-white mb-3">Analisis & Laporan</h3>
                <div class="space-y-3">
                    {{-- Ingredient Usage Analysis --}}
                    @if(Auth::user()->role->hasPermission('view_cost_analysis') && $product->ingredients->count() > 0)
                    <div class="p-3 rounded bg-neutral-800/30">
                        <h4 class="text-sm font-semibold text-white mb-2">Analisis Penggunaan Bahan</h4>
                        @php
                            $totalMaterialCost = 0;
                            $mostExpensiveIngredient = null;
                            $highestCost = 0;
                            
                            foreach($product->ingredients as $ingredient) {
                                $cost = $ingredient->pivot->quantity * ($ingredient->pivot->cost_per_unit ?? $ingredient->cost_per_unit);
                                $totalMaterialCost += $cost;
                                
                                if($cost > $highestCost) {
                                    $highestCost = $cost;
                                    $mostExpensiveIngredient = $ingredient;
                                }
                            }
                            
                            $materialCostPercentage = $product->base_price > 0 ? ($totalMaterialCost / $product->base_price) * 100 : 0;
                        @endphp
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <p class="text-neutral-400">Bahan Termahal</p>
                                <p class="text-white font-medium">{{ $mostExpensiveIngredient ? $mostExpensiveIngredient->name : 'N/A' }}</p>
                                <p class="text-xs text-neutral-400">Rp {{ number_format($highestCost, 0, ',', '.') }} per porsi</p>
                            </div>
                            <div>
                                <p class="text-neutral-400">% Biaya Bahan</p>
                                <p class="text-white font-semibold">{{ number_format($materialCostPercentage, 1) }}%</p>
                                <p class="text-xs {{ $materialCostPercentage > 40 ? 'text-red-400' : ($materialCostPercentage > 30 ? 'text-yellow-400' : 'text-green-400') }}">
                                    {{ $materialCostPercentage > 40 ? 'Tinggi' : ($materialCostPercentage > 30 ? 'Sedang' : 'Baik') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Profitability Analysis --}}
                    @if(Auth::user()->role->hasPermission('view_profit_analysis'))
                    <div class="p-3 rounded bg-neutral-800/30">
                        <h4 class="text-sm font-semibold text-white mb-2">Analisis Profitabilitas</h4>
                        @php
                            $totalCostPrice = $product->calculateFoodCost() + $product->calculateOtherCosts();
                            $grossProfit = $product->base_price - $totalCostPrice;
                            $grossMargin = $product->base_price > 0 ? ($grossProfit / $product->base_price) * 100 : 0;
                            $markup = $totalCostPrice > 0 ? (($product->base_price - $totalCostPrice) / $totalCostPrice) * 100 : 0;
                        @endphp
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div>
                                <p class="text-neutral-400">Gross Margin</p>
                                <p class="text-white font-semibold">{{ number_format($grossMargin, 1) }}%</p>
                                <p class="text-xs {{ $grossMargin >= 60 ? 'text-green-400' : ($grossMargin >= 40 ? 'text-yellow-400' : 'text-red-400') }}">
                                    {{ $grossMargin >= 60 ? 'Sangat Baik' : ($grossMargin >= 40 ? 'Baik' : 'Perlu Perbaikan') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-neutral-400">Markup</p>
                                <p class="text-white font-semibold">{{ number_format($markup, 0) }}%</p>
                                <p class="text-xs text-neutral-400">dari harga pokok</p>
                            </div>
                        </div>
                        
                        {{-- Break-even Analysis --}}
                        @if($grossProfit > 0)
                        @php
                            $dailyFixedCosts = 100000; // Estimasi biaya tetap harian (bisa disesuaikan)
                            $breakEvenUnits = ceil($dailyFixedCosts / $grossProfit);
                        @endphp
                        <div class="mt-2 pt-2 border-t border-neutral-700/50">
                            <p class="text-xs text-neutral-400">Break-even per hari:</p>
                            <p class="text-white font-semibold">{{ $breakEvenUnits }} porsi</p>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    {{-- Recommendations --}}
                    <div class="p-3 rounded border border-blue-700/50 bg-blue-900/20">
                        <h4 class="text-sm font-semibold text-blue-300 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>Rekomendasi
                        </h4>
                        <div class="space-y-1 text-xs text-neutral-300">
                            @if(isset($materialCostPercentage) && $materialCostPercentage > 40)
                            <p>• Biaya bahan terlalu tinggi ({{ number_format($materialCostPercentage, 1) }}%), cari supplier alternatif</p>
                            @endif
                            
                            @if(isset($lowStockCount) && $lowStockCount > 0)
                            <p>• Ada {{ $lowStockCount }} bahan yang perlu segera direstok</p>
                            @endif
                            
                            @if(isset($grossMargin) && $grossMargin < 40)
                            <p>• Margin rendah ({{ number_format($grossMargin, 1) }}%), pertimbangkan penyesuaian harga</p>
                            @endif
                            
                            @if($product->variants->count() == 0)
                            <p>• Pertimbangkan menambah varian untuk meningkatkan penjualan</p>
                            @endif
                            
                            @if(isset($minPortions) && $minPortions < 10)
                            <p>• Stok bahan terbatas, hanya bisa buat {{ $minPortions }} porsi</p>
                            @endif
                        </div>
                    </div>
                    
                    {{-- System Information Panel --}}
                    <div class="p-3 rounded bg-neutral-800/20">
                        <h4 class="text-sm font-semibold text-white mb-2">Informasi Sistem</h4>
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-neutral-400">ID Produk:</span>
                                <span class="text-white font-mono">{{ $product->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-400">Dibuat:</span>
                                <span class="text-white">{{ $product->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-400">Terakhir Diubah:</span>
                                <span class="text-white">{{ $product->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
