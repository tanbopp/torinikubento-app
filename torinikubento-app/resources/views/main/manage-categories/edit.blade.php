@extends('partials.layouts.main')

@section('page-title', 'Edit Kategori')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍱 Edit Kategori: {{ $category->name }}</h1>
                <p class="text-neutral-400">Perbarui informasi kategori menu resto Jepang</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('categories.show', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-eye text-sm"></i>
                    <span>Lihat Detail</span>
                </a>
                <a href="{{ route('categories.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Informasi Kategori</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Nama Kategori <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}"
                                   placeholder="Contoh: Donburi, Ramen, Sushi"
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
                                   value="{{ old('name_japanese', $category->name_japanese) }}"
                                   placeholder="Contoh: どんぶり, ラーメン, 寿司"
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
                                      rows="4"
                                      placeholder="Jelaskan tentang kategori menu ini..."
                                      class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Display Order --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Urutan Tampilan
                            </label>
                            <input type="number" 
                                   name="display_order" 
                                   value="{{ old('display_order', $category->display_order) }}"
                                   min="0"
                                   placeholder="0"
                                   class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('display_order') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-neutral-400">Semakin kecil angka, semakin atas urutannya</p>
                            @error('display_order')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-6">
                        {{-- Current Image --}}
                        @if($category->image_path)
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Gambar Saat Ini
                            </label>
                            <div class="border border-neutral-600 rounded-lg p-4">
                                <img src="{{ asset('storage/' . $category->image_path) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-32 object-cover rounded-lg">
                                <div class="flex items-center justify-center mt-3">
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
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                {{ $category->image_path ? 'Ganti Gambar' : 'Gambar Kategori' }}
                            </label>
                            <div class="border-2 border-dashed border-neutral-600 rounded-lg p-6 text-center hover:border-orange-500 transition-colors">
                                <input type="file" 
                                       name="image" 
                                       accept="image/*"
                                       id="category-image"
                                       class="hidden"
                                       onchange="previewImage(event)">
                                
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" class="mx-auto max-w-full h-32 object-cover rounded-lg mb-4">
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
                                                onclick="document.getElementById('category-image').click()"
                                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                                            Pilih Gambar {{ $category->image_path ? 'Baru' : '' }}
                                        </button>
                                        <p class="text-sm text-neutral-400 mt-2">PNG, JPG hingga 2MB</p>
                                    </div>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Status
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $category->is_active) == '1' ? 'checked' : '' }}
                                           class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 focus:ring-orange-500 focus:ring-2">
                                    <span class="ml-2 text-white">Aktif</span>
                                    <span class="ml-2 text-sm text-green-400">- Ditampilkan di menu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" 
                                           name="is_active" 
                                           value="0" 
                                           {{ old('is_active', $category->is_active) == '0' ? 'checked' : '' }}
                                           class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 focus:ring-orange-500 focus:ring-2">
                                    <span class="ml-2 text-white">Nonaktif</span>
                                    <span class="ml-2 text-sm text-neutral-400">- Disembunyikan dari menu</span>
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Info Box --}}
                        <div class="bg-green-600/10 border border-green-600/20 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-green-400 mb-2">📊 Statistik Kategori</h4>
                            <div class="text-sm text-green-300 space-y-1">
                                <p>• Total produk: <span class="font-semibold">{{ $category->products->count() }}</span></p>
                                <p>• Produk aktif: <span class="font-semibold">{{ $category->products->where('is_active', true)->count() }}</span></p>
                                <p>• Dibuat: <span class="font-semibold">{{ $category->created_at->format('d M Y') }}</span></p>
                                <p>• Terakhir diubah: <span class="font-semibold">{{ $category->updated_at->format('d M Y H:i') }}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-neutral-700">
                    <a href="{{ route('categories.index') }}" 
                       class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Batal
                    </a>
                    <a href="{{ route('categories.show', $category) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <i class="fas fa-eye text-sm"></i>
                        <span>Lihat Detail</span>
                    </a>
                    <button type="submit" 
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <i class="fas fa-save text-sm"></i>
                        <span>Perbarui Kategori</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
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
    document.getElementById('category-image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
}
</script>
@endsection
