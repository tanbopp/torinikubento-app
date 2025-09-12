@extends('partials.layouts.main')

@section('page-title', 'Edit Produk')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-end justify-between mt-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Edit Produk</h1>
                <p class="text-neutral-400">Perbarui informasi produk: {{ $product->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('products.show', $product) }}" class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-eye text-sm"></i>
                    <span>Lihat Detail</span>
                </a>
                <a href="{{ route('products.index') }}" class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-600/20 border border-green-600/30 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-400 mr-3"></i>
                <span class="text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-600/20 border border-red-600/30 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-400 mr-3"></i>
                <span class="text-red-300">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-600/20 border border-red-600/30 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-400 mr-3 mt-0.5"></i>
                <div>
                    <h4 class="text-red-300 font-medium mb-2">Ada beberapa kesalahan:</h4>
                    <ul class="text-red-300 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
            {{-- Left Column - Basic Info --}}
            <div class="lg:col-span-3 space-y-4">
                {{-- Basic Information --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <h3 class="text-sm font-semibold text-white mb-3">Informasi Dasar</h3>
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {{-- Name --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Nama Produk <span class="text-red-400">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}"
                                       placeholder="Contoh: Chicken Teriyaki Donburi"
                                       class="w-full bg-neutral-700 border-0 rounded px-3 py-2 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500 @error('name') ring-1 ring-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Japanese Name --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Nama Jepang
                                </label>
                                <input type="text" 
                                       name="name_japanese" 
                                       value="{{ old('name_japanese', $product->name_japanese) }}"
                                       placeholder="Contoh: チキン照り焼き丼"
                                       class="w-full bg-neutral-700 border-0 rounded px-3 py-2 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500 @error('name_japanese') ring-1 ring-red-500 @enderror">
                                @error('name_japanese')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Description, Category, Spice Level in one row --}}
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                            {{-- Description --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Deskripsi
                                </label>
                                <textarea name="description" 
                                          rows="2"
                                          placeholder="Jelaskan tentang produk..."
                                          class="w-full bg-neutral-700 border-0 rounded px-3 py-1 min-h-[100px] max-h-44 text-sm text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500 @error('description') ring-1 ring-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Kategori <span class="text-red-400">*</span>
                                </label>
                                <select name="category_id" class="w-full bg-neutral-700 border-0 rounded px-3 py-1 text-sm text-white focus:outline-none focus:ring-1 focus:ring-orange-500 @error('category_id') ring-1 ring-red-500 @enderror">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                        @if($category->name_japanese) ({{ $category->name_japanese }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Spice Level --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Level Pedas
                                </label>
                                <select name="spice_level" class="w-full bg-neutral-700 border-0 rounded px-3 py-1 text-sm text-white focus:outline-none focus:ring-1 focus:ring-orange-500">
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
                </div>

                {{-- Pricing & Other Costs in one row --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    {{-- Pricing --}}
                    <div class="p-3 rounded bg-neutral-800/20">
                        <h3 class="text-sm font-semibold text-white mb-3">Harga & Biaya</h3>
                        <div class="grid grid-cols-3 gap-3">
                            {{-- Selling Price --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Harga Jual <span class="text-red-400">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-xs text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="base_price" 
                                           value="{{ old('base_price', $product->base_price) }}"
                                           min="0"
                                           step="500"
                                           placeholder="25000"
                                           class="w-full bg-neutral-700 border-0 rounded pl-8 pr-2 py-1 text-base text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500 @error('base_price') ring-1 ring-red-500 @enderror">
                                </div>
                                @error('base_price')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Cost Price --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    HPP <span class="text-xs text-neutral-500">(Auto)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-xs text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="cost_price_display" 
                                           value="{{ number_format($product->cost_price, 0, ',', '.') }}"
                                           readonly
                                           disabled
                                           placeholder="Auto"
                                           class="w-full bg-neutral-600 border-0 rounded pl-8 pr-2 py-1 text-base text-neutral-400 placeholder-neutral-500 cursor-not-allowed">
                                    <input type="hidden" name="cost_price" value="{{ $product->cost_price }}">
                                </div>
                            </div>

                            {{-- Promo Price --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">
                                    Harga Promo
                                </label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 transform -translate-y-1/2 text-xs text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="promo_price" 
                                           value="{{ old('promo_price', $product->promo_price) }}"
                                           min="0"
                                           step="500"
                                           placeholder="20000"
                                           class="w-full bg-neutral-700 border-0 rounded pl-8 pr-2 py-1 text-base text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Other Costs --}}
                    <div class="p-3 rounded bg-neutral-800/20">
                        <h3 class="text-sm font-semibold text-white mb-3">Biaya Tambahan</h3>
                        <div class="space-y-2 max-h-56 overflow-y-auto">
                            @foreach($otherCosts as $otherCost)
                            @php
                                $isSelected = $product->otherCosts->contains($otherCost->id);
                                $customValue = $isSelected ? $product->otherCosts->where('id', $otherCost->id)->first()->pivot->custom_value : null;
                                $displayValue = $customValue ?? $otherCost->value;
                            @endphp
                            <div class="flex items-center space-x-2 p-2 rounded bg-neutral-700/30">
                                <input type="checkbox" 
                                       name="other_costs[]" 
                                       value="{{ $otherCost->id }}"
                                       id="other_cost_{{ $otherCost->id }}"
                                       data-type="{{ $otherCost->type }}"
                                       {{ $isSelected || collect(old('other_costs'))->contains($otherCost->id) ? 'checked' : '' }}
                                       class="rounded ml-2 border-0 bg-neutral-600 text-blue-500 focus:ring-orange-500">
                                <label for="other_cost_{{ $otherCost->id }}" class="text-sm text-neutral-300 flex-1">
                                    {{ $otherCost->name }} 
                                    @if($otherCost->type === 'percentage')
                                        ({{ number_format($otherCost->value, 1) }}%)
                                    @else
                                        (Rp {{ number_format($otherCost->value, 0, ',', '.') }})
                                    @endif
                                </label>
                                @if($otherCost->type === 'percentage')
                                    <div class="relative w-16">
                                        <input type="number" 
                                               name="other_cost_values[]" 
                                               value="{{ old('other_cost_values.' . $loop->index) ?? number_format($displayValue, 2, '.', '') }}"
                                               placeholder="{{ number_format($otherCost->value, 2, '.', '') }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               class="w-full bg-neutral-600 border-0 rounded px-2 py-1 pr-5 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        <span class="absolute right-1 top-1/2 transform -translate-y-1/2 text-xs text-neutral-400">%</span>
                                    </div>
                                @else
                                    <input type="number" 
                                           name="other_cost_values[]" 
                                           value="{{ old('other_cost_values.' . $loop->index) ?? number_format($displayValue, 0, '.', '') }}"
                                           placeholder="{{ number_format($otherCost->value, 0, '.', '') }}"
                                           step="1000"
                                           min="0"
                                           class="w-16 bg-neutral-600 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                @endif
                            </div>
                            @endforeach
                            @if($otherCosts->isEmpty())
                                <p class="text-xs text-neutral-400">Belum ada biaya lain yang tersedia.</p>
                            @endif
                        </div>
                        @error('other_costs')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Product Variants --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-white">Variasi Produk</h3>
                        <button type="button" 
                                onclick="addVariantRow()"
                                class="hover:bg-neutral-700/50 font-medium px-2 py-1 border border-neutral-700 active:bg-neutral-700 rounded-lg text-sm transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus text-sm"></i>
                            <span>Tambah</span>
                        </button>
                    </div>

                    {{-- Variants Container --}}
                    <div id="variants-container" class="space-y-2 max-h-[400px] overflow-y-auto scroll-smooth">
                        @forelse($product->variants as $index => $variant)
                            <div class="variant-row p-2 rounded bg-neutral-700/30">
                                <div class="grid grid-cols-6 gap-2 items-end">
                                    {{-- Variant Name --}}
                                    <div>
                                        <label class="block text-xs font-medium text-neutral-300 mb-1">Nama</label>
                                        <input type="text" 
                                               name="variants[{{ $index }}][name]" 
                                               value="{{ old('variants.' . $index . '.name', $variant->name) }}"
                                               placeholder="Small"
                                               class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                    </div>

                                    {{-- Variant Type --}}
                                    <div>
                                        <label class="block text-xs font-medium text-neutral-300 mb-1">Tipe</label>
                                        <select name="variants[{{ $index }}][type]" 
                                                onchange="updateVariantType(this)"
                                                class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white focus:outline-none focus:ring-1 focus:ring-orange-500">
                                            <option value="">Pilih</option>
                                            <option value="size" {{ old('variants.' . $index . '.type', $variant->type) == 'size' ? 'selected' : '' }}>Ukuran</option>
                                            <option value="spice_level" {{ old('variants.' . $index . '.type', $variant->type) == 'spice_level' ? 'selected' : '' }}>Level Pedas</option>
                                            <option value="custom" {{ old('variants.' . $index . '.type', $variant->type) == 'custom' ? 'selected' : '' }}>Kustom</option>
                                        </select>
                                    </div>

                                    {{-- Variant Value --}}
                                    <div>
                                        <label class="block text-xs font-medium text-neutral-300 mb-1">Nilai</label>
                                        <input type="text" 
                                               name="variants[{{ $index }}][value]" 
                                               value="{{ old('variants.' . $index . '.value', $variant->value) }}"
                                               placeholder="S/M/L"
                                               class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                    </div>

                                    {{-- Price Adjustment --}}
                                    <div>
                                        <label class="block text-xs font-medium text-neutral-300 mb-1">Harga +/-</label>
                                        <div class="relative">
                                            <span class="absolute left-1 top-1/2 transform -translate-y-1/2 text-neutral-400 text-xs">Rp</span>
                                            <input type="number" 
                                                   name="variants[{{ $index }}][price_adjustment]" 
                                                   value="{{ old('variants.' . $index . '.price_adjustment', $variant->price_adjustment) }}"
                                                   step="500"
                                                   placeholder="0"
                                                   class="w-full bg-neutral-700 border-0 rounded pl-5 pr-1 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        </div>
                                    </div>

                                    {{-- Sort Order & Status --}}
                                    <div>
                                        <label class="block text-xs font-medium text-neutral-300 mb-1">Urutan</label>
                                        <div class="flex items-center gap-1">
                                            <input type="number" 
                                                   name="variants[{{ $index }}][sort_order]" 
                                                   value="{{ old('variants.' . $index . '.sort_order', $variant->sort_order) }}"
                                                   min="0"
                                                   class="flex-1 bg-neutral-700 border-0 rounded px-1 py-1 text-xs text-white focus:outline-none focus:ring-1 focus:ring-orange-500">
                                            <input type="hidden" name="variants[{{ $index }}][is_active]" value="0">
                                            <input type="checkbox" 
                                                   name="variants[{{ $index }}][is_active]" 
                                                   value="1" 
                                                   {{ old('variants.' . $index . '.is_active', $variant->is_active) ? 'checked' : '' }}
                                                   class="w-3 h-3 text-orange-600 bg-neutral-600 border-0 rounded focus:ring-orange-500 focus:ring-1"
                                                   title="Aktif">
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex justify-end items-center">
                                        <button type="button" 
                                                onclick="toggleVariantDetails(this)" 
                                                class="text-neutral-400 hover:text-neutral-300 mr-2 text-xs"
                                                title="Detail">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <button type="button" 
                                                onclick="removeVariantRow(this)"
                                                class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-neutral-700 rounded-lg transition-colors duration-200">
                                            <svg class="h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17V11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11V17C9 17.2652 9.10536 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18ZM20 6H16V5C16 4.20435 15.6839 3.44129 15.1213 2.87868C14.5587 2.31607 13.7956 2 13 2H11C10.2044 2 9.44129 2.31607 8.87868 2.87868C8.31607 3.44129 8 4.20435 8 5V6H4C3.73478 6 3.48043 6.10536 3.29289 6.29289C3.10536 6.48043 3 6.73478 3 7C3 7.26522 3.10536 7.51957 3.29289 7.70711C3.48043 7.89464 3.73478 8 4 8H5V19C5 19.7956 5.31607 20.5587 5.87868 21.1213C6.44129 21.6839 7.20435 22 8 22H16C16.7956 22 17.5587 21.6839 18.1213 21.1213C18.6839 20.5587 19 19.7956 19 19V8H20C20.2652 8 20.5196 7.89464 20.7071 7.70711C20.8946 7.51957 21 7.26522 21 7C21 6.73478 20.8946 6.48043 20.7071 6.29289C20.5196 6.10536 20.2652 6 20 6ZM10 5C10 4.73478 10.1054 4.48043 10.2929 4.29289C10.4804 4.10536 10.7348 4 11 4H13C13.2652 4 13.5196 4.10536 13.7071 4.29289C13.8946 4.48043 14 4.73478 14 5V6H10V5ZM17 19C17 19.2652 16.8946 19.5196 16.7071 19.7071C16.5196 19.8946 16.2652 20 16 20H8C7.73478 20 7.48043 19.8946 7.29289 19.7071C7.10536 19.5196 7 19.2652 7 19V8H17V19ZM14 18C14.2652 18 14.5196 17.8946 14.7071 17.7071C14.8946 17.5196 15 17.2652 15 17V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10C13.7348 10 13.4804 10.1054 13.2929 10.2929C13.1054 10.4804 13 10.7348 13 11V17C13 17.2652 13.1054 17.5196 13.2929 17.7071C13.4804 17.8946 13.7348 18 14 18Z" fill="currentColor"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Additional Fields (Hidden by default) --}}
                                <div class="variant-details mt-2 hidden">
                                    <div class="grid grid-cols-2 gap-2">
                                        {{-- Japanese Name --}}
                                        <div>
                                            <label class="block text-xs font-medium text-neutral-300 mb-1">Nama Jepang</label>
                                            <input type="text" 
                                                   name="variants[{{ $index }}][name_japanese]" 
                                                   value="{{ old('variants.' . $index . '.name_japanese', $variant->name_japanese) }}"
                                                   placeholder="例：小"
                                                   class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        </div>

                                        {{-- Description --}}
                                        <div>
                                            <label class="block text-xs font-medium text-neutral-300 mb-1">Deskripsi</label>
                                            <input type="text" 
                                                   name="variants[{{ $index }}][description]" 
                                                   value="{{ old('variants.' . $index . '.description', $variant->description) }}"
                                                   placeholder="Deskripsi variasi"
                                                   class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden ID for existing variants --}}
                                @if($variant->id)
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
                                @endif
                            </div>
                        @empty
                            {{-- No variants message --}}
                        @endforelse
                    </div>

                    {{-- Variant Row Template (Hidden) --}}
                    <div id="variant-row-template" class="variant-row p-2 rounded bg-neutral-700/30 hidden">
                        <!-- Hidden field for variant ID -->
                        <input type="hidden" data-name="variants[0][id]" value="">
                        
                        <div class="grid grid-cols-6 gap-2 items-end">
                            {{-- Variant Name --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Nama</label>
                                <input type="text" 
                                       data-name="variants[0][name]" 
                                       placeholder="Small"
                                       class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                            </div>

                            {{-- Variant Type --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Tipe</label>
                                <select data-name="variants[0][type]" 
                                        onchange="updateVariantType(this)"
                                        class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white focus:outline-none focus:ring-1 focus:ring-orange-500">
                                    <option value="">Pilih</option>
                                    <option value="size">Ukuran</option>
                                    <option value="spice_level">Level Pedas</option>
                                    <option value="custom">Kustom</option>
                                </select>
                            </div>

                            {{-- Variant Value --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Nilai</label>
                                <input type="text" 
                                       data-name="variants[0][value]" 
                                       placeholder="S/M/L"
                                       class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                            </div>

                            {{-- Price Adjustment --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Harga +/-</label>
                                <div class="relative">
                                    <span class="absolute left-1 top-1/2 transform -translate-y-1/2 text-neutral-400 text-xs">Rp</span>
                                    <input type="number" 
                                           data-name="variants[0][price_adjustment]" 
                                           value="0"
                                           step="500"
                                           placeholder="0"
                                           class="w-full bg-neutral-700 border-0 rounded pl-5 pr-1 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                </div>
                            </div>

                            {{-- Sort Order & Status --}}
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Urutan</label>
                                <div class="flex items-center gap-1">
                                    <input type="number" 
                                           data-name="variants[0][sort_order]" 
                                           value="0"
                                           min="0"
                                           class="flex-1 bg-neutral-700 border-0 rounded px-1 py-1 text-xs text-white focus:outline-none focus:ring-1 focus:ring-orange-500">
                                    <input type="hidden" data-name="variants[0][is_active]" value="0">
                                    <input type="checkbox" 
                                           data-name="variants[0][is_active]" 
                                           value="1" 
                                           checked
                                           class="w-3 h-3 text-orange-600 bg-neutral-600 border-0 rounded focus:ring-orange-500 focus:ring-1"
                                           title="Aktif">
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex justify-end items-center">
                                <button type="button" 
                                        onclick="toggleVariantDetails(this)" 
                                        class="text-neutral-400 hover:text-neutral-300 mr-2 text-xs"
                                        title="Detail">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <button type="button" 
                                        onclick="removeVariantRow(this)"
                                        class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-neutral-700 rounded-lg transition-colors duration-200">
                                    <svg class="h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17V11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11V17C9 17.2652 9.10536 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18ZM20 6H16V5C16 4.20435 15.6839 3.44129 15.1213 2.87868C14.5587 2.31607 13.7956 2 13 2H11C10.2044 2 9.44129 2.31607 8.87868 2.87868C8.31607 3.44129 8 4.20435 8 5V6H4C3.73478 6 3.48043 6.10536 3.29289 6.29289C3.10536 6.48043 3 6.73478 3 7C3 7.26522 3.10536 7.51957 3.29289 7.70711C3.48043 7.89464 3.73478 8 4 8H5V19C5 19.7956 5.31607 20.5587 5.87868 21.1213C6.44129 21.6839 7.20435 22 8 22H16C16.7956 22 17.5587 21.6839 18.1213 21.1213C18.6839 20.5587 19 19.7956 19 19V8H20C20.2652 8 20.5196 7.89464 20.7071 7.70711C20.8946 7.51957 21 7.26522 21 7C21 6.73478 20.8946 6.48043 20.7071 6.29289C20.5196 6.10536 20.2652 6 20 6ZM10 5C10 4.73478 10.1054 4.48043 10.2929 4.29289C10.4804 4.10536 10.7348 4 11 4H13C13.2652 4 13.5196 4.10536 13.7071 4.29289C13.8946 4.48043 14 4.73478 14 5V6H10V5ZM17 19C17 19.2652 16.8946 19.5196 16.7071 19.7071C16.5196 19.8946 16.2652 20 16 20H8C7.73478 20 7.48043 19.8946 7.29289 19.7071C7.10536 19.5196 7 19.2652 7 19V8H17V19ZM14 18C14.2652 18 14.5196 17.8946 14.7071 17.7071C14.8946 17.5196 15 17.2652 15 17V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10C13.7348 10 13.4804 10.1054 13.2929 10.2929C13.1054 10.4804 13 10.7348 13 11V17C13 17.2652 13.1054 17.5196 13.2929 17.7071C13.4804 17.8946 13.7348 18 14 18Z" fill="currentColor"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Additional Fields (Hidden by default) --}}
                        <div class="variant-details mt-2 hidden">
                            <div class="grid grid-cols-2 gap-2">
                                {{-- Japanese Name --}}
                                <div>
                                    <label class="block text-xs font-medium text-neutral-300 mb-1">Nama Jepang</label>
                                    <input type="text" 
                                           data-name="variants[0][name_japanese]" 
                                           placeholder="例：小"
                                           class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label class="block text-xs font-medium text-neutral-300 mb-1">Deskripsi</label>
                                    <input type="text" 
                                           data-name="variants[0][description]" 
                                           placeholder="Deskripsi variasi"
                                           class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- No Variants Message --}}
                    @if($product->variants->count() == 0)
                        <div id="no-variants-message" class="text-center py-4 text-neutral-400">
                            <i class="fas fa-layer-group text-lg mb-1"></i>
                            <p class="text-xs">Belum ada variasi produk.</p>
                        </div>
                    @else
                        <div id="no-variants-message" class="text-center py-4 text-neutral-400 hidden">
                            <i class="fas fa-layer-group text-lg mb-1"></i>
                            <p class="text-xs">Belum ada variasi produk.</p>
                        </div>
                    @endif
                </div>

                {{-- BOM (Bill of Materials) --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-white">Bill of Materials (BOM)</h3>
                        <button type="button" 
                                onclick="addIngredientRow()"
                                class="hover:bg-neutral-700/50 font-medium px-2 py-1 border border-neutral-700 active:bg-neutral-700 rounded-lg text-sm transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus text-sm"></i>
                            <span>Tambah</span>
                        </button>
                    </div>

                    {{-- Ingredients List --}}
                    <div id="ingredients-container" class="space-y-2 max-h-[564px] overflow-y-auto scroll-smooth">
                        {{-- Existing ingredients --}}
                        @foreach($product->ingredients as $index => $ingredient)
                        <div class="ingredient-row p-2 rounded bg-neutral-700/30">
                            <div class="grid grid-cols-6 gap-2 items-end">
                                {{-- Ingredient Selection --}}
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-neutral-300 mb-1">Bahan Baku</label>
                                    <select name="ingredients[{{ $index }}][ingredient_id]" class="ingredient-select w-full bg-neutral-600 border-0 rounded px-2 py-1 h-[28px] text-sm text-white focus:outline-none focus:ring-1 focus:ring-orange-500" onchange="updateIngredientInfo(this)">
                                        <option value="">Pilih Bahan</option>
                                        @foreach($ingredients as $availableIngredient)
                                        <option value="{{ $availableIngredient->id }}" 
                                                {{ $ingredient->id == $availableIngredient->id ? 'selected' : '' }}
                                                data-unit="{{ $availableIngredient->unit }}" 
                                                data-cost="{{ $availableIngredient->cost_per_unit }}"
                                                data-stock="{{ $availableIngredient->stock_quantity }}">
                                            {{ $availableIngredient->name }}
                                            @if($availableIngredient->name_japanese) ({{ $availableIngredient->name_japanese }}) @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Quantity --}}
                                <div>
                                    <label class="block text-xs font-medium text-neutral-300 mb-1">Qty</label>
                                    <input type="number" 
                                           name="ingredients[{{ $index }}][quantity]" 
                                           value="{{ $ingredient->pivot->quantity }}"
                                           class="quantity-input w-full bg-neutral-600 border-0 rounded px-2 py-1 text-sm text-white focus:outline-none focus:ring-1 focus:ring-orange-500"
                                           min="0.01" 
                                           step="0.01" 
                                           placeholder="1.0"
                                           onchange="calculateRowCost(this)">
                                </div>

                                {{-- Unit --}}
                                <div>
                                    <label class="block text-xs font-medium text-neutral-300 mb-1">Unit</label>
                                    <input type="text" 
                                           name="ingredients[{{ $index }}][unit]" 
                                           value="{{ $ingredient->pivot->unit ?? $ingredient->unit }}"
                                           class="unit-display w-full bg-neutral-600 border-0 rounded px-2 py-1 text-sm text-neutral-400 cursor-not-allowed"
                                           readonly 
                                           placeholder="kg">
                                </div>

                                {{-- Cost --}}
                                <div>
                                    <label class="block text-xs font-medium text-right text-neutral-300 mb-1">Biaya</label>
                                    <div class="row-cost text-sm py-1 min-w-sm text-right">Rp {{ number_format($ingredient->pivot->quantity * $ingredient->cost_per_unit, 0, ',', '.') }}</div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex justify-end items-center">
                                    <button type="button" 
                                            onclick="removeIngredientRow(this)"
                                            class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-white hover:bg-neutral-700 rounded-lg transition-colors duration-200">
                                        <svg class="h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17V11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11V17C9 17.2652 9.10536 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18ZM20 6H16V5C16 4.20435 15.6839 3.44129 15.1213 2.87868C14.5587 2.31607 13.7956 2 13 2H11C10.2044 2 9.44129 2.31607 8.87868 2.87868C8.31607 3.44129 8 4.20435 8 5V6H4C3.73478 6 3.48043 6.10536 3.29289 6.29289C3.10536 6.48043 3 6.73478 3 7C3 7.26522 3.10536 7.51957 3.29289 7.70711C3.48043 7.89464 3.73478 8 4 8H5V19C5 19.7956 5.31607 20.5587 5.87868 21.1213C6.44129 21.6839 7.20435 22 8 22H16C16.7956 22 17.5587 21.6839 18.1213 21.1213C18.6839 20.5587 19 19.7956 19 19V8H20C20.2652 8 20.5196 7.89464 20.7071 7.70711C20.8946 7.51957 21 7.26522 21 7C21 6.73478 20.8946 6.48043 20.7071 6.29289C20.5196 6.10536 20.2652 6 20 6ZM10 5C10 4.73478 10.1054 4.48043 10.2929 4.29289C10.4804 4.10536 10.7348 4 11 4H13C13.2652 4 13.5196 4.10536 13.7071 4.29289C13.8946 4.48043 14 4.73478 14 5V6H10V5ZM17 19C17 19.2652 16.8946 19.5196 16.7071 19.7071C16.5196 19.8946 16.2652 20 16 20H8C7.73478 20 7.48043 19.8946 7.29289 19.7071C7.10536 19.5196 7 19.2652 7 19V8H17V19ZM14 18C14.2652 18 14.5196 17.8946 14.7071 17.7071C14.8946 17.5196 15 17.2652 15 17V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10C13.7348 10 13.4804 10.1054 13.2929 10.2929C13.1054 10.4804 13 10.7348 13 11V17C13 17.2652 13.1054 17.5196 13.2929 17.7071C13.4804 17.8946 13.7348 18 14 18Z" fill="currentColor"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Total Cost Display --}}
                    <div class="mt-3 p-2 rounded bg-neutral-700/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-neutral-300">Total Biaya Bahan:</span>
                            <div class="text-sm font-medium">
                                Rp <span id="total-cost-display">{{ number_format($product->calculateFoodCost(), 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-sm text-neutral-400">HPP (auto):</span>
                            <div class="text-sm font-medium text-neutral-300">
                                Rp <span id="cost-price-display">{{ number_format($product->cost_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Compact Settings --}}
            <div class="space-y-4">
                {{-- Image Upload/Current --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <h3 class="text-sm font-semibold text-white mb-2">Gambar Produk</h3>
                    
                    {{-- Hidden upload input for all cases --}}
                    <input type="file" 
                           name="image" 
                           accept="image/*"
                           id="product-image"
                           class="hidden"
                           onchange="handleImageSelect(event)">
                    
                    <input type="hidden" name="cropped_image" id="cropped-image-data">
                    <input type="hidden" name="remove_image" id="remove-image-flag" value="0">

                    @if($product->image_path)
                    {{-- Current Image Display --}}
                    <div id="current-image" class="mb-3">
                        <div class="w-full aspect-square overflow-hidden rounded bg-neutral-700/30 mx-auto relative group">
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                            
                            <div class="flex items-center justify-center space-x-3 absolute w-full left-0 right-0 bottom-0 divide-x divide-neutral-500 bg-black/70 backdrop-blur-lg py-2">
                                <button type="button" 
                                        onclick="event.stopPropagation(); removeCurrentImage()"
                                        class="text-neutral-300 hover:text-white text-sm w-full">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Upload Area (shown when no image or for uploading new image) --}}
                    <div class="border-[2px] aspect-square border-dotted border-neutral-600 rounded p-3 text-center hover:border-orange-500 transition-colors flex flex-col justify-center items-center group {{ $product->image_path ? 'hidden' : 'flex' }}" 
                         id="drop-zone"
                         onclick="document.getElementById('product-image').click()"
                         ondragover="handleDragOver(event)"
                         ondragenter="handleDragEnter(event)" 
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">
                        
                        <div id="upload-placeholder" class="space-y-2 pointer-events-none">
                            <div class="w-8 h-8 bg-neutral-700 rounded-full flex items-center justify-center mx-auto group-hover:bg-orange-600 transition-colors">
                                <i class="fas fa-camera text-sm text-neutral-400 group-hover:text-white"></i>
                            </div>
                            <div class="font-medium text-sm group-hover:underline">
                                Upload atau Drop di sini
                            </div>
                            <p class="text-xs text-neutral-400">PNG/JPG, 2MB max</p>
                        </div>
                    </div>

                    {{-- Preview area for new uploaded images (replaces current image or upload area) --}}
                    <div id="image-preview" class="hidden">
                        <div class="w-full aspect-square border-[2px] border-dotted border-orange-500 p-1 relative">
                            <div class="w-full h-full overflow-hidden bg-neutral-950 mx-auto">
                                <img id="preview-img" class="w-full h-full object-cover">
                            </div>
                            <div class="flex items-center justify-center space-x-3 absolute w-full left-0 right-0 bottom-0 divide-x divide-neutral-500 bg-black/70 backdrop-blur-lg py-2">
                                <button type="button" 
                                        onclick="event.stopPropagation(); openCropModal()"
                                        class="text-neutral-300 hover:text-white text-sm w-full">
                                    <i class="fas fa-crop mr-2"></i>Crop
                                </button>
                                <button type="button" 
                                        onclick="event.stopPropagation(); removeNewImage()"
                                        class="text-neutral-300 hover:text-white text-sm w-full">
                                    <i class="fas fa-trash mr-2"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <h3 class="text-sm font-semibold text-white mb-2">Pengaturan</h3>
                    <div class="space-y-2">
                        {{-- Status toggles --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <label class="text-xs font-medium text-neutral-300 mb-1">Aktif</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-neutral-600 rounded-full peer peer-checked:bg-white transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full border border-neutral-400 peer-checked:translate-x-full peer-checked:border-white transition-transform duration-300"></div>
                                </label>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs font-medium text-neutral-300 mb-1">Tersedia</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_available" value="0">
                                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-neutral-600 rounded-full peer peer-checked:bg-white transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full border border-neutral-400 peer-checked:translate-x-full peer-checked:border-white transition-transform duration-300"></div>
                                </label>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs font-medium text-neutral-300 mb-1">Musiman</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_seasonal" value="0">
                                    <input type="checkbox" name="is_seasonal" value="1" {{ old('is_seasonal', $product->is_seasonal) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-neutral-600 rounded-full peer peer-checked:bg-white transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full border border-neutral-400 peer-checked:translate-x-full peer-checked:border-white transition-transform duration-300"></div>
                                </label>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-xs font-medium text-neutral-300 mb-1">Limited</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_limited_edition" value="0">
                                    <input type="checkbox" name="is_limited_edition" value="1" {{ old('is_limited_edition', $product->is_limited_edition) == '1' ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-9 h-5 bg-neutral-600 rounded-full peer peer-checked:bg-white transition-colors duration-300"></div>
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-neutral-500 rounded-full border border-neutral-400 peer-checked:translate-x-full peer-checked:border-white transition-transform duration-300"></div>
                                </label>
                            </div>
                        </div>

                        {{-- Numeric inputs --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Batas Harian</label>
                                <input type="number" 
                                       name="daily_limit" 
                                       value="{{ old('daily_limit', $product->daily_limit) }}"
                                       min="0"
                                       placeholder="50"
                                       class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-300 mb-1">Urutan</label>
                                <input type="number" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', $product->sort_order) }}"
                                       min="0"
                                       placeholder="0"
                                       class="w-full bg-neutral-700 border-0 rounded px-2 py-1 text-xs text-white placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="p-3 rounded bg-neutral-800/20">
                    <h4 class="text-sm font-semibold text-white mb-2">Statistik</h4>
                    <div class="text-xs text-neutral-300 space-y-1">
                        <div class="flex justify-between">
                            <span>Margin:</span>
                            <span>{{ number_format($product->margin_percentage, 1) }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Profit:</span>
                            <span>Rp {{ number_format($product->base_price - $product->cost_price, 0, ',', '.') }}</span>
                        </div>
                        @if($product->otherCosts->count() > 0)
                        <div class="flex justify-between">
                            <span>Biaya Lain:</span>
                            <span>Rp {{ number_format($product->calculateOtherCosts(), 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Dibuat:</span>
                            <span>{{ $product->created_at->format('d/m/y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-neutral-800">
            <a href="{{ route('products.index') }}" 
               class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-4 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="bg-orange-600 hover:bg-orange-700 text-green-100 font-medium px-4 py-2 active:bg-orange-700 rounded-xl transition-colors flex items-center space-x-2">
                <span>Perbarui</span>
            </button>
        </div>
    </form>
</div>

{{-- Image Cropper Modal --}}
<div id="crop-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center">
    <div class="bg-neutral-800 rounded-lg w-full max-w-5xl mx-4 max-h-[90vh] overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-700 flex items-center justify-between">
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
                <div class="text-xs text-neutral-400">
                    <p><i class="fas fa-info-circle text-xs mr-2"></i>Drag untuk memindahkan, scroll untuk zoom</p>
                    <p class="mt-1">Rasio akan otomatis menjadi 1:1 (square)</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button type="button" 
                            onclick="closeCropModal()" 
                            class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                        Batal
                    </button>
                    <button type="button" 
                            onclick="applyCrop()" 
                            class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                        Terapkan
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

// Drag and Drop Functions
function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    const dropZone = document.getElementById('drop-zone');
    dropZone.classList.add('border-orange-500', 'bg-orange-500/10');
}

function handleDragEnter(event) {
    event.preventDefault();
    event.stopPropagation();
    const dropZone = document.getElementById('drop-zone');
    dropZone.classList.add('border-orange-500', 'bg-orange-500/10');
}

function handleDragLeave(event) {
    event.preventDefault();
    event.stopPropagation();
    
    // Only remove classes if we're leaving the drop zone completely
    if (!event.currentTarget.contains(event.relatedTarget)) {
        const dropZone = document.getElementById('drop-zone');
        dropZone.classList.remove('border-orange-500', 'bg-orange-500/10');
    }
}

function handleDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropZone = document.getElementById('drop-zone');
    dropZone.classList.remove('border-orange-500', 'bg-orange-500/10');
    
    const files = event.dataTransfer.files;
    
    if (files.length > 0) {
        const file = files[0];
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('File harus berupa gambar (PNG, JPG, JPEG).');
            return;
        }
        
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB.');
            return;
        }
        
        // Update file input and trigger processing
        const fileInput = document.getElementById('product-image');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        // Process the file
        currentFile = file;
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
            
            // Show preview and hide other elements
            showImagePreview();
            
            closeCropModal();
        }, 'image/jpeg', 0.9);
    }
}

function showImagePreview() {
    // Hide current image and upload area
    const currentImage = document.getElementById('current-image');
    const dropZone = document.getElementById('drop-zone');
    const imagePreview = document.getElementById('image-preview');
    
    if (currentImage) {
        currentImage.classList.add('hidden');
    }
    if (dropZone) {
        dropZone.classList.add('hidden');
    }
    
    // Show preview
    imagePreview.classList.remove('hidden');
    
    // Reset remove flag since user is uploading new image
    const removeImageFlag = document.getElementById('remove-image-flag');
    if (removeImageFlag) {
        removeImageFlag.value = '0';
        console.log('Reset remove_image flag to 0 (new image uploaded)');
    }
}

function removeNewImage() {
    console.log('removeNewImage() called');
    
    // Clear the file input and cropped data
    document.getElementById('product-image').value = '';
    document.getElementById('cropped-image-data').value = '';
    
    // Hide preview
    const imagePreview = document.getElementById('image-preview');
    imagePreview.classList.add('hidden');
    
    // Show appropriate element back
    const currentImage = document.getElementById('current-image');
    const dropZone = document.getElementById('drop-zone');
    
    if (currentImage) {
        // If product has existing image, show it back BUT mark for removal
        currentImage.classList.remove('hidden');
        
        // Set the remove_image flag to 1
        const removeImageFlag = document.getElementById('remove-image-flag');
        if (removeImageFlag) {
            removeImageFlag.value = '1';
            console.log('Set remove_image flag to 1 from removeNewImage');
        }
    } else if (dropZone) {
        // If no existing image, show upload area
        dropZone.classList.remove('hidden');
    }
    
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
}

function removeImage() {
    removeNewImage();
}

function removeCurrentImage() {
    console.log('removeCurrentImage() called');
    
    // Hide the current image container
    const currentImage = document.getElementById('current-image');
    if (currentImage) {
        currentImage.classList.add('hidden');
    }
    
    // Show the upload area
    const dropZone = document.getElementById('drop-zone');
    if (dropZone) {
        dropZone.classList.remove('hidden');
        dropZone.classList.add('flex');
    }
    
    // Set the remove_image flag to 1
    const removeImageFlag = document.getElementById('remove-image-flag');
    if (removeImageFlag) {
        removeImageFlag.value = '1';
        console.log('Set remove_image flag to 1');
    }
    
    // Also clear any existing image uploads to ensure clean state
    document.getElementById('product-image').value = '';
    document.getElementById('cropped-image-data').value = '';
    
    // Hide any preview that might be showing
    const imagePreview = document.getElementById('image-preview');
    if (imagePreview) {
        imagePreview.classList.add('hidden');
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

// Variant Management Functions  
let variantRowIndex = {{ count($product->variants) }};

function addVariantRow() {
    const container = document.getElementById('variants-container');
    const template = document.getElementById('variant-row-template');
    
    if (!template) {
        console.error('Template not found!');
        return;
    }
    
    const newRow = template.cloneNode(true);
    
    // Remove template ID and show the row
    newRow.removeAttribute('id');
    newRow.classList.remove('hidden');
    
    // Get current highest index from existing variants
    const existingVariants = container.querySelectorAll('.variant-row:not(#variant-row-template)');
    let highestIndex = variantRowIndex;
    
    // Find the actual highest index in use
    existingVariants.forEach(row => {
        const inputs = row.querySelectorAll('input[name*="variants["], select[name*="variants["]');
        inputs.forEach(input => {
            const match = input.name.match(/variants\[(\d+)\]/);
            if (match) {
                const index = parseInt(match[1]);
                if (index >= highestIndex) {
                    highestIndex = index + 1;
                }
            }
        });
    });
    
    // Convert data-name attributes to name attributes with the new index
    const elements = newRow.querySelectorAll('input[data-name], select[data-name]');
    
    elements.forEach(element => {
        if (element.getAttribute('data-name')) {
            const dataName = element.getAttribute('data-name');
            element.name = dataName.replace('[0]', `[${highestIndex}]`);
            element.removeAttribute('data-name');
        }
    });
    
    container.appendChild(newRow);
    variantRowIndex = highestIndex + 1;
    
    // Hide no variants message
    const noVariantsMessage = document.getElementById('no-variants-message');
    if (noVariantsMessage) {
        noVariantsMessage.classList.add('hidden');
    }
}

function removeVariantRow(button) {
    const row = button.closest('.variant-row');
    row.remove();
    
    // Show no variants message if no variants left
    const container = document.getElementById('variants-container');
    const visibleVariants = container.querySelectorAll('.variant-row:not(.hidden):not(#variant-row-template)');
    const noVariantsMessage = document.getElementById('no-variants-message');
    
    if (visibleVariants.length === 0 && noVariantsMessage) {
        noVariantsMessage.classList.remove('hidden');
    }
}

function updateVariantType(selectElement) {
    const row = selectElement.closest('.variant-row');
    const valueInput = row.querySelector('input[name*="[value]"]');
    const type = selectElement.value;
    
    // Update placeholder and suggestions based on type
    switch(type) {
        case 'size':
            valueInput.placeholder = 'Contoh: S, M, L atau Small, Medium, Large';
            break;
        case 'spice_level':
            valueInput.placeholder = 'Contoh: 1, 2, 3, 4, 5';
            break;
        case 'custom':
            valueInput.placeholder = 'Nilai kustom';
            break;
        default:
            valueInput.placeholder = 'Masukkan nilai variasi';
    }
}

function toggleVariantDetails(button) {
    const row = button.closest('.variant-row');
    const detailsSection = row.querySelector('.variant-details');
    const icon = button.querySelector('i');
    
    if (detailsSection.classList.contains('hidden')) {
        detailsSection.classList.remove('hidden');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
        button.title = 'Sembunyikan Detail';
    } else {
        detailsSection.classList.add('hidden');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
        button.title = 'Detail';
    }
}
</script>
@endsection
