@extends('partials.layouts.main')

@section('page-title', 'Edit Bahan Baku')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🥬 Edit Bahan Baku</h1>
                <p class="text-neutral-400">Edit informasi bahan baku {{ $ingredient->name }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('ingredients.show', $ingredient) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-eye text-sm"></i>
                    <span>Lihat</span>
                </a>
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Edit Informasi Bahan Baku</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('ingredients.update', $ingredient) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Nama Bahan Baku <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $ingredient->name) }}"
                                   placeholder="Contoh: Ayam Teriyaki, Beras Sushi, Nori"
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
                                   value="{{ old('name_japanese', $ingredient->name_japanese) }}"
                                   placeholder="Contoh: 照り焼きチキン, 寿司米, 海苔"
                                   class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name_japanese') border-red-500 @enderror">
                            @error('name_japanese')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      rows="3"
                                      placeholder="Jelaskan tentang bahan baku ini..."
                                      class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $ingredient->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type and Unit --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Jenis <span class="text-red-400">*</span>
                                </label>
                                <select name="type" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('type') border-red-500 @enderror">
                                    <option value="">Pilih Jenis</option>
                                    @foreach($types as $key => $label)
                                        <option value="{{ $key }}" {{ old('type', $ingredient->type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Satuan <span class="text-red-400">*</span>
                                </label>
                                <select name="unit" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500 @error('unit') border-red-500 @enderror">
                                    <option value="">Pilih Satuan</option>
                                    @foreach($units as $key => $label)
                                        <option value="{{ $key }}" {{ old('unit', $ingredient->unit) === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('unit')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Cost per Unit --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Harga per Satuan <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-neutral-400">Rp</span>
                                <input type="number" 
                                       name="cost_per_unit" 
                                       value="{{ old('cost_per_unit', $ingredient->cost_per_unit) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg pl-10 pr-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('cost_per_unit') border-red-500 @enderror">
                            </div>
                            @error('cost_per_unit')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Stock Information --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium text-neutral-300 border-b border-neutral-600 pb-2">Informasi Stok</h4>
                            
                            {{-- Current Stock --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Stok Saat Ini <span class="text-red-400">*</span>
                                </label>
                                <input type="number" 
                                       name="current_stock" 
                                       value="{{ old('current_stock', $ingredient->current_stock) }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('current_stock') border-red-500 @enderror">
                                @error('current_stock')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Min and Max Stock --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-300 mb-2">
                                        Stok Minimum <span class="text-red-400">*</span>
                                    </label>
                                    <input type="number" 
                                           name="minimum_stock" 
                                           value="{{ old('minimum_stock', $ingredient->minimum_stock) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('minimum_stock') border-red-500 @enderror">
                                    @error('minimum_stock')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-neutral-300 mb-2">
                                        Stok Maksimum <span class="text-red-400">*</span>
                                    </label>
                                    <input type="number" 
                                           name="maximum_stock" 
                                           value="{{ old('maximum_stock', $ingredient->maximum_stock) }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00"
                                           class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('maximum_stock') border-red-500 @enderror">
                                    @error('maximum_stock')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Additional Information --}}
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium text-neutral-300 border-b border-neutral-600 pb-2">Informasi Tambahan</h4>
                            
                            {{-- Shelf Life --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Masa Simpan (hari)
                                </label>
                                <input type="number" 
                                       name="shelf_life_days" 
                                       value="{{ old('shelf_life_days', $ingredient->shelf_life_days) }}"
                                       min="1"
                                       placeholder="Contoh: 7, 30, 365"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('shelf_life_days') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-neutral-400">Kosongkan jika tidak terbatas</p>
                                @error('shelf_life_days')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Storage Instructions --}}
                            <div>
                                <label class="block text-sm font-medium text-neutral-300 mb-2">
                                    Instruksi Penyimpanan
                                </label>
                                <textarea name="storage_instruction" 
                                          rows="3"
                                          placeholder="Contoh: Simpan di kulkas suhu 4°C, jauhkan dari sinar matahari langsung"
                                          class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('storage_instruction') border-red-500 @enderror">{{ old('storage_instruction', $ingredient->storage_instruction) }}</textarea>
                                @error('storage_instruction')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $ingredient->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500 focus:ring-2">
                                <span class="text-sm text-neutral-300">Aktif (bahan baku dapat digunakan)</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-between pt-6 border-t border-neutral-700">
                    <a href="{{ route('ingredients.show', $ingredient) }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Bahan Baku
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Current Values Info --}}
    <div class="mt-6 bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Nilai Saat Ini</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm text-neutral-400 mb-1">Stok Sekarang</p>
                    <p class="text-xl font-bold text-white">{{ number_format($ingredient->current_stock, 2) }} {{ $ingredient->unit }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-neutral-400 mb-1">Nilai Stok</p>
                    <p class="text-xl font-bold text-orange-400">Rp {{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 0, ',', '.') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-neutral-400 mb-1">Status Stok</p>
                    @if($ingredient->isLowStock())
                        <p class="text-xl font-bold text-red-400">Rendah</p>
                    @else
                        <p class="text-xl font-bold text-green-400">Normal</p>
                    @endif
                </div>
                <div class="text-center">
                    <p class="text-sm text-neutral-400 mb-1">Terakhir Update</p>
                    <p class="text-sm text-white">{{ $ingredient->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate maximum stock based on minimum stock
    const minStockInput = document.querySelector('input[name="minimum_stock"]');
    const maxStockInput = document.querySelector('input[name="maximum_stock"]');
    
    minStockInput?.addEventListener('input', function() {
        const minValue = parseFloat(this.value) || 0;
        const currentMaxValue = parseFloat(maxStockInput.value) || 0;
        
        // Only update if max is less than min
        if (currentMaxValue < minValue) {
            maxStockInput.value = (minValue * 2).toFixed(2);
        }
    });
});
</script>
@endsection
