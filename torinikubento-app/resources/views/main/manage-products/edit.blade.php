@extends('partials.layouts.main')

@section('page-title', 'Edit Produk')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍜 Edit Produk: {{ $product->name }}</h1>
                <p class="text-neutral-400">Perbarui informasi produk menu resto Jepang</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('products.show', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-eye text-sm"></i>
                    <span>Lihat Detail</span>
                </a>
                <a href="{{ route('products.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Left Column - Basic Info --}}
            <div class="xl:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Informasi Dasar</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Nama Produk <span class="text-red-400">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Contoh: Chicken Teriyaki Donburi"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Japanese Name --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Nama Jepang
                                </label>
                                <input type="text" 
                                       name="name_japanese" 
                                       value="{{ old('name_japanese', $product->name_japanese) }}"
                                       placeholder="Contoh: チキン照り焼き丼"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name_japanese') border-red-500 @enderror">
                                @error('name_japanese')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      rows="4"
                                      placeholder="Jelaskan tentang produk ini, bahan-bahan, cita rasa, dll..."
                                      class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category and Spice Level --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Category --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Kategori <span class="text-red-400">*</span>
                                </label>
                                <select name="category_id" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('category_id') border-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                        @if($category->name_japanese) ({{ $category->name_japanese }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Other Costs (Biaya Lain) --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Biaya Lain
                                </label>
                                <div class="space-y-2">
                                    @foreach($otherCosts as $otherCost)
                                    @php
                                        $isSelected = $product->otherCosts->contains($otherCost->id);
                                        $customValue = $isSelected ? $product->otherCosts->where('id', $otherCost->id)->first()->pivot->custom_value : null;
                                        $displayValue = $customValue ?? $otherCost->value;
                                    @endphp
                                    <div class="flex items-center space-x-2">
                                        <input type="checkbox" 
                                               name="other_costs[]" 
                                               value="{{ $otherCost->id }}"
                                               id="other_cost_{{ $otherCost->id }}"
                                               data-type="{{ $otherCost->type }}"
                                               {{ $isSelected || collect(old('other_costs'))->contains($otherCost->id) ? 'checked' : '' }}
                                               class="rounded border-neutral-600 bg-neutral-700 text-orange-500 focus:ring-orange-500">
                                        <label for="other_cost_{{ $otherCost->id }}" class="text-sm text-neutral-300 flex-1">
                                            {{ $otherCost->name }} 
                                            @if($otherCost->type === 'percentage')
                                                ({{ number_format($otherCost->value, 2) }}%)
                                            @else
                                                (Rp {{ number_format($otherCost->value, 0, ',', '.') }})
                                            @endif
                                        </label>
                                        @if($otherCost->type === 'percentage')
                                            <div class="relative w-24">
                                                <input type="number" 
                                                       name="other_cost_values[]" 
                                                       value="{{ old('other_cost_values.' . $loop->index) ?? number_format($displayValue, 2, '.', '') }}"
                                                       placeholder="{{ number_format($otherCost->value, 2, '.', '') }}"
                                                       step="0.01"
                                                       min="0"
                                                       max="100"
                                                       class="w-full bg-neutral-700 border border-neutral-600 rounded px-2 py-1 pr-6 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                                <span class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs text-neutral-400">%</span>
                                            </div>
                                        @else
                                            <input type="number" 
                                                   name="other_cost_values[]" 
                                                   value="{{ old('other_cost_values.' . $loop->index) ?? number_format($displayValue, 0, '.', '') }}"
                                                   placeholder="{{ number_format($otherCost->value, 0, '.', '') }}"
                                                   step="1000"
                                                   min="0"
                                                   class="w-24 bg-neutral-700 border border-neutral-600 rounded px-2 py-1 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        @endif
                                    </div>
                                    @endforeach
                                    @if($otherCosts->isEmpty())
                                        <p class="text-sm text-neutral-400">Belum ada biaya lain yang tersedia.</p>
                                    @endif
                                </div>
                                @error('other_costs')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Spice Level --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Level Pedas
                            </label>
                            <select name="spice_level" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="0" {{ old('spice_level', $product->spice_level) == 0 ? 'selected' : '' }}>Tidak Pedas</option>
                                <option value="1" {{ old('spice_level', $product->spice_level) == 1 ? 'selected' : '' }}>🌶️ Level 1</option>
                                <option value="2" {{ old('spice_level', $product->spice_level) == 2 ? 'selected' : '' }}>🌶️🌶️ Level 2</option>
                                <option value="3" {{ old('spice_level', $product->spice_level) == 3 ? 'selected' : '' }}>🌶️🌶️🌶️ Level 3</option>
                                <option value="4" {{ old('spice_level', $product->spice_level) == 4 ? 'selected' : '' }}>🌶️🌶️🌶️🌶️ Level 4</option>
                                <option value="5" {{ old('spice_level', $product->spice_level) == 5 ? 'selected' : '' }}>🌶️🌶️🌶️🌶️🌶️ Level 5</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Harga & Biaya</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Selling Price --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Harga Jual <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="base_price" 
                                           value="{{ old('base_price', $product->base_price) }}"
                                           min="0"
                                           step="500"
                                           placeholder="25000"
                                           class="w-full bg-neutral-700 border border-neutral-600 rounded-lg pl-10 pr-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('base_price') border-red-500 @enderror">
                                </div>
                                @error('base_price')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Cost Price --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Harga Pokok <span class="text-xs text-neutral-500">(Auto Calculated)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="cost_price_display" 
                                           value="{{ number_format($product->cost_price, 0, ',', '.') }}"
                                           readonly
                                           disabled
                                           placeholder="Akan dihitung otomatis"
                                           class="w-full bg-neutral-600 border border-neutral-500 rounded-lg pl-10 pr-3 py-2 text-neutral-400 placeholder-neutral-500 cursor-not-allowed">
                                    <input type="hidden" name="cost_price" value="{{ $product->cost_price }}">
                                </div>
                                <p class="mt-1 text-xs text-neutral-400">Saat ini: Rp {{ number_format($product->cost_price, 0, ',', '.') }} (Dihitung dari BOM)</p>
                            </div>

                            {{-- Promo Price --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Harga Promo
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="promo_price" 
                                           value="{{ old('promo_price', $product->promo_price) }}"
                                           min="0"
                                           step="500"
                                           placeholder="20000"
                                           class="w-full bg-neutral-700 border border-neutral-600 rounded-lg pl-10 pr-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                <p class="mt-1 text-xs text-neutral-400">Kosongkan jika tidak ada promo</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOM (Bill of Materials) --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Bill of Materials (BOM)</h3>
                        <p class="text-sm text-neutral-400 mt-1">Tentukan bahan baku dan komposisi untuk produk ini</p>
                    </div>
                    <div class="p-6">
                        {{-- Add Ingredient Button --}}
                        <div class="mb-4">
                            <button type="button" 
                                    onclick="addIngredientRow()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                                <i class="fas fa-plus text-sm"></i>
                                <span>Tambah Bahan Baku</span>
                            </button>
                        </div>

                        {{-- Ingredients List --}}
                        <div id="ingredients-container" class="space-y-4">
                            {{-- Existing ingredients --}}
                            @foreach($product->ingredients as $index => $ingredient)
                            <div class="ingredient-row bg-neutral-700 rounded-lg p-4 border border-neutral-600">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                    {{-- Ingredient Selection --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Bahan Baku</label>
                                        <select name="ingredients[{{ $index }}][ingredient_id]" class="ingredient-select w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="updateIngredientInfo(this)">
                                            <option value="">Pilih Bahan Baku</option>
                                            @foreach($ingredients as $availableIngredient)
                                            <option value="{{ $availableIngredient->id }}" 
                                                    {{ $ingredient->id == $availableIngredient->id ? 'selected' : '' }}
                                                    data-unit="{{ $availableIngredient->unit }}" 
                                                    data-cost="{{ $availableIngredient->cost_per_unit }}"
                                                    data-stock="{{ $availableIngredient->stock_quantity }}">
                                                {{ $availableIngredient->name }}
                                                @if($availableIngredient->name_japanese) ({{ $availableIngredient->name_japanese }}) @endif
                                                - Rp {{ number_format($availableIngredient->cost_per_unit, 0, ',', '.') }}/{{ $availableIngredient->unit }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Quantity --}}
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Jumlah</label>
                                        <input type="number" 
                                               name="ingredients[{{ $index }}][quantity]" 
                                               value="{{ $ingredient->pivot->quantity }}"
                                               class="quantity-input w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
                                               min="0.01" 
                                               step="0.01" 
                                               placeholder="1.00"
                                               onchange="calculateRowCost(this)">
                                    </div>

                                    {{-- Unit (readonly) --}}
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Satuan</label>
                                        <input type="text" 
                                               name="ingredients[{{ $index }}][unit]" 
                                               value="{{ $ingredient->pivot->unit ?? $ingredient->unit }}"
                                               class="unit-display w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-neutral-400 cursor-not-allowed"
                                               readonly 
                                               placeholder="kg/pcs/ml">
                                    </div>

                                    {{-- Row Actions --}}
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm text-neutral-300">
                                            <span class="block text-xs text-neutral-400">Biaya:</span>
                                            <span class="row-cost font-semibold text-orange-400">Rp {{ number_format($ingredient->pivot->quantity * $ingredient->cost_per_unit, 0, ',', '.') }}</span>
                                        </div>
                                        <button type="button" 
                                                onclick="removeIngredientRow(this)"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Optional ingredient checkbox --}}
                                <div class="mt-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="ingredients[{{ $index }}][is_optional]" 
                                               value="1"
                                               {{ $ingredient->pivot->is_optional ? 'checked' : '' }}
                                               class="text-orange-600 bg-neutral-600 border-neutral-500 rounded focus:ring-orange-500 mr-2">
                                        <span class="text-sm text-neutral-300">Bahan opsional (tidak wajib)</span>
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Total Cost Display --}}
                        <div class="mt-6 p-4 bg-neutral-700 rounded-lg border border-neutral-600">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-neutral-300">Total Biaya Bahan Baku:</span>
                                <div class="text-lg font-bold text-orange-400">
                                    Rp <span id="total-cost-display">{{ number_format($product->calculateFoodCost(), 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-neutral-400">Harga Pokok (auto-calculated):</span>
                                <div class="text-sm font-semibold text-neutral-300">
                                    Rp <span id="cost-price-display">{{ number_format($product->cost_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Template untuk ingredient row (hidden) --}}
                        <div id="ingredient-row-template" class="hidden">
                            <div class="ingredient-row bg-neutral-700 rounded-lg p-4 border border-neutral-600">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                    {{-- Ingredient Selection --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Bahan Baku</label>
                                        <select name="ingredients[INDEX][ingredient_id]" class="ingredient-select w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500" onchange="updateIngredientInfo(this)">
                                            <option value="">Pilih Bahan Baku</option>
                                            @foreach($ingredients as $ingredient)
                                            <option value="{{ $ingredient->id }}" 
                                                    data-unit="{{ $ingredient->unit }}" 
                                                    data-cost="{{ $ingredient->cost_per_unit }}"
                                                    data-stock="{{ $ingredient->stock_quantity }}">
                                                {{ $ingredient->name }}
                                                @if($ingredient->name_japanese) ({{ $ingredient->name_japanese }}) @endif
                                                - Rp {{ number_format($ingredient->cost_per_unit, 0, ',', '.') }}/{{ $ingredient->unit }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Quantity --}}
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Jumlah</label>
                                        <input type="number" 
                                               name="ingredients[INDEX][quantity]" 
                                               class="quantity-input w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500"
                                               min="0.01" 
                                               step="0.01" 
                                               placeholder="1.00"
                                               onchange="calculateRowCost(this)">
                                    </div>

                                    {{-- Unit (readonly) --}}
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-300 mb-2">Satuan</label>
                                        <input type="text" 
                                               name="ingredients[INDEX][unit]" 
                                               class="unit-display w-full bg-neutral-600 border border-neutral-500 rounded-lg px-3 py-2 text-neutral-400 cursor-not-allowed"
                                               readonly 
                                               placeholder="kg/pcs/ml">
                                    </div>

                                    {{-- Row Actions --}}
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm text-neutral-300">
                                            <span class="block text-xs text-neutral-400">Biaya:</span>
                                            <span class="row-cost font-semibold text-orange-400">Rp 0</span>
                                        </div>
                                        <button type="button" 
                                                onclick="removeIngredientRow(this)"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Optional ingredient checkbox --}}
                                <div class="mt-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="ingredients[INDEX][is_optional]" 
                                               value="1"
                                               class="text-orange-600 bg-neutral-600 border-neutral-500 rounded focus:ring-orange-500 mr-2">
                                        <span class="text-sm text-neutral-300">Bahan opsional (tidak wajib)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Settings --}}
            <div class="space-y-6">
                {{-- Current Image --}}
                @if($product->image_path)
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Gambar Saat Ini</h3>
                    </div>
                    <div class="p-6">
                        <div class="mx-auto w-40 h-40 overflow-hidden rounded-lg mb-4 border-2 border-neutral-600">
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex items-center justify-center">
                            <label class="flex items-center">
                                <input type="checkbox" name="remove_image" value="1" 
                                       class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500 focus:ring-2">
                                <span class="ml-2 text-sm text-red-400">Hapus gambar ini</span>
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Image Upload --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">{{ $product->image_path ? 'Ganti Gambar' : 'Gambar Produk' }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="border-2 border-dashed border-neutral-600 rounded-lg p-6 text-center hover:border-orange-500 transition-colors">
                            <input type="file" 
                                   name="image" 
                                   accept="image/*"
                                   id="product-image"
                                   class="hidden"
                                   onchange="handleImageSelect(event)">
                            
                            <input type="hidden" name="cropped_image" id="cropped-image-data">
                            
                            <div id="image-preview" class="hidden">
                                <div class="mx-auto w-40 h-40 overflow-hidden rounded-lg mb-4 border-2 border-neutral-600">
                                    <img id="preview-img" class="w-full h-full object-cover">
                                </div>
                                <div class="flex items-center justify-center space-x-3">
                                    <button type="button" 
                                            onclick="openCropModal()"
                                            class="text-orange-400 hover:text-orange-300 text-sm">
                                        <i class="fas fa-crop mr-1"></i> Crop Ulang
                                    </button>
                                    <button type="button" 
                                            onclick="removeImage()"
                                            class="text-red-400 hover:text-red-300 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Hapus Gambar Baru
                                    </button>
                                </div>
                            </div>
                            
                            <div id="upload-placeholder" class="space-y-4">
                                <div class="mx-auto w-16 h-16 bg-neutral-700 rounded-full flex items-center justify-center">
                                    <i class="fas fa-camera text-2xl text-neutral-400"></i>
                                </div>
                                <div>
                                    <button type="button" 
                                            onclick="document.getElementById('product-image').click()"
                                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                                        Pilih Gambar {{ $product->image_path ? 'Baru' : '' }}
                                    </button>
                                    <p class="text-sm text-neutral-400 mt-2">PNG, JPG hingga 2MB</p>
                                    <p class="text-xs text-neutral-500 mt-1">Gambar akan di-crop ke rasio 1:1 (square)</p>
                                </div>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Product Settings --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Pengaturan</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Status Settings --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-neutral-300">Status Aktif</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-neutral-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-neutral-300">Tersedia</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_available" value="0">
                                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-neutral-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-neutral-300">Produk Musiman</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_seasonal" value="0">
                                    <input type="checkbox" name="is_seasonal" value="1" {{ old('is_seasonal', $product->is_seasonal) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-neutral-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium text-neutral-300">Limited Edition</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_limited_edition" value="0">
                                    <input type="checkbox" name="is_limited_edition" value="1" {{ old('is_limited_edition', $product->is_limited_edition) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-neutral-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                                </label>
                            </div>
                        </div>

                        {{-- Limits --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Batas Harian
                                </label>
                                <input type="number" 
                                       name="daily_limit" 
                                       value="{{ old('daily_limit', $product->daily_limit) }}"
                                       min="0"
                                       placeholder="Contoh: 50"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <p class="mt-1 text-xs text-neutral-400">Kosongkan untuk tidak ada batas</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Urutan Tampilan
                                </label>
                                <input type="number" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $product->sort_order) }}"
                                       min="0"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <p class="mt-1 text-xs text-neutral-400">Semakin kecil, semakin atas</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Stats --}}
                <div class="bg-green-600/10 border border-green-600/20 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-green-400 mb-2">📊 Statistik Produk</h4>
                    <div class="text-sm text-green-300 space-y-1">
                        <p>• Margin saat ini: <span class="font-semibold">{{ number_format($product->margin_percentage, 1) }}%</span></p>
                        <p>• Profit per item: <span class="font-semibold">Rp {{ number_format($product->base_price - $product->cost_price, 0, ',', '.') }}</span></p>
                        @if($product->otherCosts->count() > 0)
                        <p>• Biaya Lain: 
                            @foreach($product->otherCosts as $otherCost)
                                <span class="font-semibold">{{ $otherCost->name }}</span>@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p>• Total Biaya Lain: <span class="font-semibold">Rp {{ number_format($product->calculateOtherCosts(), 0, ',', '.') }}</span></p>
                        @else
                        <p>• Biaya Lain: <span class="text-neutral-400">Tidak ada biaya lain</span></p>
                        @endif
                        <p>• Dibuat: <span class="font-semibold">{{ $product->created_at->format('d M Y') }}</span></p>
                        <p>• Terakhir diubah: <span class="font-semibold">{{ $product->updated_at->format('d M Y H:i') }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-neutral-700">
            <a href="{{ route('products.index') }}" 
               class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg transition-colors">
                Batal
            </a>
            <a href="{{ route('products.show', $product) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <i class="fas fa-eye text-sm"></i>
                <span>Lihat Detail</span>
            </a>
            <button type="submit" 
                    class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <i class="fas fa-save text-sm"></i>
                <span>Perbarui Produk</span>
            </button>
        </div>
    </form>
</div>

{{-- Image Cropper Modal --}}
<div id="crop-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center">
    <div class="bg-neutral-800 rounded-lg border border-neutral-700 w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-neutral-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Crop Gambar Produk</h3>
            <button type="button" onclick="closeCropModal()" class="text-neutral-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="p-6">
            <div class="bg-neutral-900 rounded-lg overflow-hidden mb-6" style="max-height: 400px;">
                <img id="crop-image" class="max-w-full" style="display: block;">
            </div>
            
            <div class="flex items-center justify-between">
                <div class="text-sm text-neutral-400">
                    <p><i class="fas fa-info-circle mr-2"></i>Drag untuk memindahkan, scroll untuk zoom</p>
                    <p class="mt-1">Rasio akan otomatis menjadi 1:1 (square)</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            onclick="closeCropModal()" 
                            class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="button" 
                            onclick="applyCrop()" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include Cropper.js from CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
let cropper;
let currentFile;

function handleImageSelect(event) {
    const file = event.target.files[0];
    if (file) {
        currentFile = file;
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar (PNG, JPG, JPEG).');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('crop-image').src = e.target.result;
            openCropModal();
        };
        reader.readAsDataURL(file);
    }
}

function openCropModal() {
    const modal = document.getElementById('crop-modal');
    const image = document.getElementById('crop-image');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Initialize cropper
    setTimeout(() => {
        if (cropper) {
            cropper.destroy();
        }
        
        cropper = new Cropper(image, {
            aspectRatio: 1, // Square aspect ratio
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false,
            background: false,
            modal: true,
            guides: true,
            center: true,
            highlight: true,
            responsive: true,
            restore: true,
            checkCrossOrigin: true,
            checkOrientation: true,
            scalable: true,
            zoomable: true,
            zoomOnTouch: true,
            zoomOnWheel: true,
            wheelZoomRatio: 0.1,
            cropBoxData: null,
            canvasData: null,
        });
    }, 100);
}

function closeCropModal() {
    const modal = document.getElementById('crop-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function applyCrop() {
    if (cropper) {
        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });
        
        canvas.toBlob(function(blob) {
            // Create preview
            const previewImg = document.getElementById('preview-img');
            previewImg.src = canvas.toDataURL();
            
            // Store cropped image data
            document.getElementById('cropped-image-data').value = canvas.toDataURL('image/jpeg', 0.9);
            
            // Update UI
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
            
            closeCropModal();
        }, 'image/jpeg', 0.9);
    }
}

function removeImage() {
    document.getElementById('product-image').value = '';
    document.getElementById('cropped-image-data').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

// Close modal when clicking outside
document.getElementById('crop-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCropModal();
    }
});

// Prevent modal close when clicking inside modal content
document.querySelector('#crop-modal .bg-neutral-800').addEventListener('click', function(e) {
    e.stopPropagation();
});

// BOM Management Functions
let ingredientRowIndex = {{ count($product->ingredients) }};

function addIngredientRow() {
    const container = document.getElementById('ingredients-container');
    const template = document.getElementById('ingredient-row-template');
    const newRow = template.cloneNode(true);
    
    // Remove template ID and show the row
    newRow.removeAttribute('id');
    newRow.classList.remove('hidden');
    
    // Update all name attributes with current index
    const inputs = newRow.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace('INDEX', ingredientRowIndex);
        }
    });
    
    container.appendChild(newRow);
    ingredientRowIndex++;
    
    // Update cost calculation
    updateTotalCost();
}

function removeIngredientRow(button) {
    const row = button.closest('.ingredient-row');
    row.remove();
    updateTotalCost();
}

function updateIngredientInfo(selectElement) {
    const row = selectElement.closest('.ingredient-row');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (selectedOption.value) {
        const unit = selectedOption.getAttribute('data-unit');
        const cost = selectedOption.getAttribute('data-cost');
        const stock = selectedOption.getAttribute('data-stock');
        
        // Update unit display
        const unitInput = row.querySelector('.unit-display');
        unitInput.value = unit;
        
        // Update cost calculation
        calculateRowCost(selectElement);
    } else {
        // Clear unit and cost if no ingredient selected
        const unitInput = row.querySelector('.unit-display');
        unitInput.value = '';
        
        const rowCost = row.querySelector('.row-cost');
        rowCost.textContent = 'Rp 0';
        
        updateTotalCost();
    }
}

function calculateRowCost(element) {
    const row = element.closest('.ingredient-row');
    const selectElement = row.querySelector('.ingredient-select');
    const quantityInput = row.querySelector('.quantity-input');
    const rowCostDisplay = row.querySelector('.row-cost');
    
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (selectedOption.value && quantityInput.value) {
        const costPerUnit = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const totalRowCost = costPerUnit * quantity;
        
        rowCostDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalRowCost);
    } else {
        rowCostDisplay.textContent = 'Rp 0';
    }
    
    updateTotalCost();
}

function updateTotalCost() {
    let totalCost = 0;
    
    // Calculate total from all ingredient rows
    const rows = document.querySelectorAll('.ingredient-row:not(#ingredient-row-template)');
    rows.forEach(row => {
        const selectElement = row.querySelector('.ingredient-select');
        const quantityInput = row.querySelector('.quantity-input');
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        
        if (selectedOption.value && quantityInput.value) {
            const costPerUnit = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;
            totalCost += (costPerUnit * quantity);
        }
    });

    // Calculate other costs
    let otherCostsTotal = 0;
    const otherCostCheckboxes = document.querySelectorAll('input[name="other_costs[]"]:checked');
    const otherCostValues = document.querySelectorAll('input[name="other_cost_values[]"]');
    
    otherCostCheckboxes.forEach((checkbox, index) => {
        const costValue = parseFloat(otherCostValues[index]?.value) || 0;
        const costType = checkbox.getAttribute('data-type') || 'fixed';
        
        if (costType === 'percentage') {
            otherCostsTotal += totalCost * (costValue / 100);
        } else {
            otherCostsTotal += costValue;
        }
    });

    // Total cost price = ingredient cost + other costs
    const finalCostPrice = totalCost + otherCostsTotal;
    
    // Update displays
    document.getElementById('total-cost-display').textContent = new Intl.NumberFormat('id-ID').format(totalCost);
    document.getElementById('cost-price-display').textContent = new Intl.NumberFormat('id-ID').format(finalCostPrice);
    
    // Update the cost price hidden input
    const costPriceInput = document.querySelector('input[name="cost_price"]');
    if (costPriceInput) {
        costPriceInput.value = finalCostPrice;
    }
    
    // Update the cost price display input
    const costPriceDisplayInput = document.querySelector('input[name="cost_price_display"]');
    if (costPriceDisplayInput) {
        costPriceDisplayInput.value = finalCostPrice;
    }

    // Auto-update selling price if not manually changed
    autoUpdateSellingPrice(finalCostPrice);
}

function autoUpdateSellingPrice(costPrice) {
    const basePriceInput = document.querySelector('input[name="base_price"]');
    
    // If base price equals the previous cost price, auto-update it
    if (basePriceInput && (parseFloat(basePriceInput.value) === parseFloat(basePriceInput.getAttribute('data-previous-cost')) || basePriceInput.getAttribute('data-auto-updated') === 'true')) {
        basePriceInput.value = Math.ceil(costPrice); // Round up to nearest integer
        basePriceInput.setAttribute('data-previous-cost', costPrice);
        basePriceInput.setAttribute('data-auto-updated', 'true');
    }
}

// Initialize cost calculation on page load
document.addEventListener('DOMContentLoaded', function() {
    const basePriceInput = document.querySelector('input[name="base_price"]');
    if (basePriceInput) {
        // Set initial previous cost
        basePriceInput.setAttribute('data-previous-cost', {{ $product->cost_price ?? 0 }});
        
        basePriceInput.addEventListener('input', function() {
            // Mark as manually changed
            this.setAttribute('data-auto-updated', 'false');
        });
    }

    // Add event listeners for other costs
    const otherCostCheckboxes = document.querySelectorAll('input[name="other_costs[]"]');
    const otherCostValues = document.querySelectorAll('input[name="other_cost_values[]"]');
    
    otherCostCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalCost);
    });
    
    otherCostValues.forEach(input => {
        input.addEventListener('input', updateTotalCost);
    });

    updateTotalCost();
});
</script>
@endsection
