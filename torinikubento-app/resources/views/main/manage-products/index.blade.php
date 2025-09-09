@extends('partials.layouts.main')

@section('page-title', 'Manajemen Produk')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍜 Manajemen Produk</h1>
                <p class="text-neutral-400">Kelola menu dan produk resto Jepang Toriniku Bento</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('view_analytics'))
                <a href="{{ route('products.menu-engineering') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-chart-pie text-sm"></i>
                    <span>Menu Engineering</span>
                </a>
                @endif
                @if(Auth::user()->role->hasPermission('edit_products'))
                <form method="POST" action="{{ route('products.update-cost-prices') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2"
                            onclick="return confirm('Update semua harga pokok produk berdasarkan biaya bahan saat ini?')">
                        <i class="fas fa-calculator text-sm"></i>
                        <span>Update Biaya</span>
                    </button>
                </form>
                @endif
                @if(Auth::user()->role->hasPermission('create_products'))
                <a href="{{ route('products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Produk</span>
                </a>
                @endif
            </div>
        </div>
    </div>

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

    {{-- Filter Section --}}
    <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Filter & Pencarian</h3>
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama produk atau nama Jepang"
                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Kategori</label>
                <select name="category_id" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Status</label>
                <select name="status" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Ketersediaan</label>
                <select name="availability" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua</option>
                    <option value="1" {{ request('availability') === '1' ? 'selected' : '' }}>Tersedia</option>
                    <option value="0" {{ request('availability') === '0' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Musiman</label>
                <select name="seasonal" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua</option>
                    <option value="1" {{ request('seasonal') === '1' ? 'selected' : '' }}>Musiman</option>
                    <option value="0" {{ request('seasonal') === '0' ? 'selected' : '' }}>Reguler</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('products.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Daftar Produk</h3>
        </div>
        <div class="p-6">
            @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-neutral-700">
                            <th class="pb-3 text-neutral-300 font-medium">Gambar</th>
                            <th class="pb-3 text-neutral-300 font-medium">Produk</th>
                            <th class="pb-3 text-neutral-300 font-medium">Kategori</th>
                            <th class="pb-3 text-neutral-300 font-medium">Harga</th>
                            @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                            <th class="pb-3 text-neutral-300 font-medium">Biaya/Margin</th>
                            @endif
                            <th class="pb-3 text-neutral-300 font-medium">Status</th>
                            <th class="pb-3 text-neutral-300 font-medium">Tipe</th>
                            <th class="pb-3 text-neutral-300 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($products as $product)
                        <tr class="border-b border-neutral-700/50 hover:bg-neutral-700/30">
                            <td class="py-4">
                                @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-16 h-16 rounded-lg object-cover">
                                @else
                                <div class="w-16 h-16 bg-neutral-700 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-utensils text-neutral-400 text-xl"></i>
                                </div>
                                @endif
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $product->name }}</p>
                                    @if($product->name_japanese)
                                    <p class="text-sm text-blue-400">{{ $product->name_japanese }}</p>
                                    @endif
                                    @if($product->description)
                                    <p class="text-sm text-neutral-400 mt-1">{{ Str::limit($product->description, 60) }}</p>
                                    @endif
                                    @if($product->spice_level > 0)
                                    <p class="text-sm text-red-400 mt-1">
                                        🌶️ Level {{ $product->spice_level }}
                                    </p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-700 text-neutral-300">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div>
                                    @if($product->promo_price && $product->promo_price < $product->base_price)
                                    <p class="text-sm text-neutral-400 line-through">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                    <p class="font-semibold text-red-400">
                                        Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                                    </p>
                                    @else
                                    <p class="font-semibold text-white">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                    @endif
                                </div>
                            </td>
                            @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                            <td class="py-4">
                                <div>
                                    <p class="text-sm text-neutral-400">HPP: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $product->margin_percentage > 30 ? 'bg-green-600/20 text-green-400' : ($product->margin_percentage > 15 ? 'bg-yellow-600/20 text-yellow-400' : 'bg-red-600/20 text-red-400') }}">
                                        {{ number_format($product->margin_percentage, 1) }}% margin
                                    </span>
                                </div>
                            </td>
                            @endif
                            <td class="py-4">
                                <div class="space-y-1">
                                    @if($product->is_active)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-600/20 text-green-400">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-600/20 text-red-400">
                                        Nonaktif
                                    </span>
                                    @endif
                                    
                                    @if($product->is_available)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-600/20 text-blue-400">
                                        Tersedia
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-600/20 text-orange-400">
                                        Habis
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="space-y-1">
                                    @if($product->is_seasonal)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-600/20 text-purple-400">
                                        🌸 Musiman
                                    </span>
                                    @endif
                                    @if($product->is_limited_edition)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-600/20 text-yellow-400">
                                        ⭐ Limited
                                    </span>
                                    @endif
                                    @if($product->daily_limit)
                                    <p class="text-xs text-neutral-400">Max: {{ $product->daily_limit }}/hari</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    @if(Auth::user()->role->hasPermission('view_products'))
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('edit_products'))
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="text-neutral-400 hover:text-white" title="Lainnya">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-neutral-700 border border-neutral-600 rounded-lg shadow-lg z-10">
                                            <form method="POST" action="{{ route('products.toggle-status', $product) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-600">
                                                    <i class="fas fa-{{ $product->is_active ? 'times' : 'check' }} mr-2"></i>
                                                    {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('products.toggle-availability', $product) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-600">
                                                    <i class="fas fa-{{ $product->is_available ? 'times-circle' : 'check-circle' }} mr-2"></i>
                                                    {{ $product->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('delete_products'))
                                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-300" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} 
                    dari {{ $products->total() }} produk
                </div>
                <div class="flex items-center space-x-2">
                    {{ $products->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-utensils text-6xl text-neutral-600 mb-4"></i>
                <p class="text-xl text-neutral-400 mb-4">Belum ada produk</p>
                @if(Auth::user()->role->hasPermission('create_products'))
                <a href="{{ route('products.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Produk Pertama</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
