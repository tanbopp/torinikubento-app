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
                </div>

                {{-- Pricing --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Harga & Biaya</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Base Price --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Harga Dasar <span class="text-red-400">*</span>
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
                                    Harga Pokok (HPP)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">Rp</span>
                                    <input type="number" 
                                           name="cost_price" 
                                           value="{{ old('cost_price', $product->cost_price) }}"
                                           min="0"
                                           step="500"
                                           placeholder="15000"
                                           class="w-full bg-neutral-700 border border-neutral-600 rounded-lg pl-10 pr-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </div>
                                <p class="mt-1 text-xs text-neutral-400">Saat ini: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
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
                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-40 object-cover rounded-lg mb-4">
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
                                   onchange="previewImage(event)">
                            
                            <div id="image-preview" class="hidden">
                                <img id="preview-img" class="mx-auto max-w-full h-40 object-cover rounded-lg mb-4">
                                <button type="button" 
                                        onclick="removeImage()"
                                        class="text-red-400 hover:text-red-300 text-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus Gambar Baru
                                </button>
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

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('product-image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
}
</script>
@endsection
