@extends('partials.layouts.main')

@section('page-title', 'Detail Kategori')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍱 {{ $category->name }}</h1>
                @if($category->name_japanese)
                <p class="text-lg text-blue-400 mb-2">{{ $category->name_japanese }}</p>
                @endif
                <p class="text-neutral-400">Detail lengkap kategori menu resto Jepang</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('edit_categories'))
                <a href="{{ route('categories.edit', $category) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-edit text-sm"></i>
                    <span>Edit Kategori</span>
                </a>
                @endif
                <a href="{{ route('categories.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column - Category Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Category Image & Basic Info --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6">
                    {{-- Image --}}
                    <div class="mb-6">
                        @if($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" 
                             alt="{{ $category->name }}" 
                             class="w-full h-48 object-cover rounded-lg">
                        @else
                        <div class="w-full h-48 bg-neutral-700 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-6xl text-neutral-400"></i>
                        </div>
                        @endif
                    </div>

                    {{-- Basic Details --}}
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ $category->name }}</h3>
                            @if($category->name_japanese)
                            <p class="text-blue-400">{{ $category->name_japanese }}</p>
                            @endif
                        </div>

                        @if($category->description)
                        <div>
                            <h4 class="text-sm font-medium text-neutral-300 mb-2">Deskripsi</h4>
                            <p class="text-neutral-400">{{ $category->description }}</p>
                        </div>
                        @endif

                        {{-- Status --}}
                        <div>
                            <h4 class="text-sm font-medium text-neutral-300 mb-2">Status</h4>
                            @if($category->is_active)
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
                        </div>

                        {{-- Display Order --}}
                        <div>
                            <h4 class="text-sm font-medium text-neutral-300 mb-2">Urutan Tampilan</h4>
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-neutral-700 rounded-full text-lg font-medium text-neutral-300">
                                {{ $category->display_order }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Stats --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Statistik</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Total Produk</span>
                        <span class="text-xl font-bold text-white">{{ $category->products->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Produk Aktif</span>
                        <span class="text-lg font-semibold text-green-400">{{ $category->products->where('is_active', true)->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Produk Nonaktif</span>
                        <span class="text-lg font-semibold text-red-400">{{ $category->products->where('is_active', false)->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Tersedia</span>
                        <span class="text-lg font-semibold text-blue-400">{{ $category->products->where('is_available', true)->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Category Info --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Informasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Dibuat</span>
                        <span class="text-white">{{ $category->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Terakhir Diubah</span>
                        <span class="text-white">{{ $category->updated_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-300">Umur</span>
                        <span class="text-white">{{ $category->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            @if(Auth::user()->role->hasPermission('edit_categories'))
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                </div>
                <div class="p-6 space-y-3">
                    <form method="POST" action="{{ route('categories.toggle-status', $category) }}" class="w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full bg-{{ $category->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $category->is_active ? 'red' : 'green' }}-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-{{ $category->is_active ? 'times' : 'check' }} mr-2"></i>
                            {{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Kategori
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column - Products List --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Products in this Category --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="p-6 border-b border-neutral-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Produk dalam Kategori</h3>
                        @if(Auth::user()->role->hasPermission('create_products'))
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center space-x-2">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Tambah Produk</span>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($category->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->products as $product)
                        <div class="bg-neutral-700 rounded-lg border border-neutral-600 p-4 hover:bg-neutral-600/50 transition-colors">
                            <div class="flex items-start space-x-4">
                                {{-- Product Image --}}
                                <div class="flex-shrink-0">
                                    @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                    <div class="w-16 h-16 bg-neutral-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-utensils text-neutral-400"></i>
                                    </div>
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-sm font-semibold text-white truncate">{{ $product->name }}</h4>
                                            @if($product->name_japanese)
                                            <p class="text-xs text-blue-400 mt-1">{{ $product->name_japanese }}</p>
                                            @endif
                                            
                                            {{-- Price --}}
                                            <div class="mt-2">
                                                @if($product->promo_price && $product->promo_price < $product->base_price)
                                                <span class="text-xs text-neutral-400 line-through">
                                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                </span>
                                                <span class="text-sm font-semibold text-red-400 ml-2">
                                                    Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                                                </span>
                                                @else
                                                <span class="text-sm font-semibold text-white">
                                                    Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                </span>
                                                @endif
                                            </div>

                                            {{-- Status Badges --}}
                                            <div class="flex items-center space-x-2 mt-2">
                                                @if($product->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-600/20 text-green-400">
                                                    Aktif
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-600/20 text-red-400">
                                                    Nonaktif
                                                </span>
                                                @endif

                                                @if($product->is_available)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-600/20 text-blue-400">
                                                    Tersedia
                                                </span>
                                                @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-600/20 text-orange-400">
                                                    Habis
                                                </span>
                                                @endif

                                                @if($product->is_seasonal)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-600/20 text-purple-400">
                                                    🌸 Musiman
                                                </span>
                                                @endif

                                                @if($product->is_limited_edition)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-600/20 text-yellow-400">
                                                    ⭐ Limited
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(Auth::user()->role->hasPermission('view_products'))
                                            <a href="{{ route('products.show', $product) }}" 
                                               class="text-blue-400 hover:text-blue-300" title="Lihat">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @endif
                                            @if(Auth::user()->role->hasPermission('edit_products'))
                                            <a href="{{ route('products.edit', $product) }}" 
                                               class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-utensils text-6xl text-neutral-600 mb-4"></i>
                        <p class="text-xl text-neutral-400 mb-4">Belum ada produk di kategori ini</p>
                        <p class="text-neutral-500 mb-6">Tambah produk pertama untuk kategori {{ $category->name }}</p>
                        @if(Auth::user()->role->hasPermission('create_products'))
                        <a href="{{ route('products.create') }}?category_id={{ $category->id }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Produk Pertama</span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
