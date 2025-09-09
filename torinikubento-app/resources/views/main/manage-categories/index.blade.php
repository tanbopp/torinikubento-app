@extends('partials.layouts.main')

@section('page-title', 'Manajemen Kategori')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">🍱 Manajemen Kategori</h1>
                <p class="text-neutral-400">Kelola kategori menu resto Jepang Toriniku Bento</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role->hasPermission('create_categories'))
                <a href="{{ route('categories.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Kategori</span>
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
        <form method="GET" action="{{ route('categories.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama kategori atau nama Jepang"
                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-300 mb-2">Status</label>
                <select name="status" class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('categories.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Categories Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Daftar Kategori</h3>
        </div>
        <div class="p-6">
            @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-neutral-700">
                            <th class="pb-3 text-neutral-300 font-medium">Gambar</th>
                            <th class="pb-3 text-neutral-300 font-medium">Nama Kategori</th>
                            <th class="pb-3 text-neutral-300 font-medium">Jumlah Produk</th>
                            <th class="pb-3 text-neutral-300 font-medium">Status</th>
                            <th class="pb-3 text-neutral-300 font-medium">Urutan</th>
                            <th class="pb-3 text-neutral-300 font-medium">Dibuat</th>
                            <th class="pb-3 text-neutral-300 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($categories as $category)
                        <tr class="border-b border-neutral-700/50 hover:bg-neutral-700/30">
                            <td class="py-4">
                                @if($category->image_path)
                                <img src="{{ asset('storage/' . $category->image_path) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-16 h-16 rounded-lg object-cover">
                                @else
                                <div class="w-16 h-16 bg-neutral-700 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder text-neutral-400 text-xl"></i>
                                </div>
                                @endif
                            </td>
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $category->name }}</p>
                                    @if($category->name_japanese)
                                    <p class="text-sm text-blue-400">{{ $category->name_japanese }}</p>
                                    @endif
                                    @if($category->description)
                                    <p class="text-sm text-neutral-400 mt-1">{{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-lg font-semibold text-white">{{ $category->products->count() }}</span>
                                    <span class="text-sm text-neutral-400">produk</span>
                                </div>
                                @if($category->products->where('is_active', true)->count() > 0)
                                <p class="text-xs text-green-400 mt-1">
                                    {{ $category->products->where('is_active', true)->count() }} aktif
                                </p>
                                @endif
                            </td>
                            <td class="py-4">
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
                            </td>
                            <td class="py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-neutral-700 rounded-full text-sm font-medium text-neutral-300">
                                    {{ $category->display_order }}
                                </span>
                            </td>
                            <td class="py-4">
                                <p class="text-sm text-neutral-300">{{ $category->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-neutral-400">{{ $category->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    @if(Auth::user()->role->hasPermission('view_categories'))
                                    <a href="{{ route('categories.show', $category) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('edit_categories'))
                                    <a href="{{ route('categories.edit', $category) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('categories.toggle-status', $category) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-{{ $category->is_active ? 'orange' : 'green' }}-400 hover:text-{{ $category->is_active ? 'orange' : 'green' }}-300" 
                                                title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $category->is_active ? 'toggle-off' : 'toggle-on' }}"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('delete_categories'))
                                    <form method="POST" action="{{ route('categories.destroy', $category) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-400 hover:text-red-300" 
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus kategori ini? Semua produk dalam kategori ini akan kehilangan kategorinya.')">
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
                    Menampilkan {{ $categories->firstItem() ?? 0 }} sampai {{ $categories->lastItem() ?? 0 }} 
                    dari {{ $categories->total() }} kategori
                </div>
                <div class="flex items-center space-x-2">
                    {{ $categories->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-folder text-6xl text-neutral-600 mb-4"></i>
                <p class="text-xl text-neutral-400 mb-4">Belum ada kategori</p>
                <p class="text-neutral-500 mb-6">Buat kategori pertama untuk mengorganisir menu resto Anda</p>
                @if(Auth::user()->role->hasPermission('create_categories'))
                <a href="{{ route('categories.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kategori Pertama</span>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Quick Stats --}}
    @if($categories->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-600/20 rounded-lg">
                    <i class="fas fa-folder text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Total Kategori</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-blue-600/20 rounded-lg">
                    <i class="fas fa-check-circle text-blue-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Kategori Aktif</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-600/20 rounded-lg">
                    <i class="fas fa-utensils text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Total Produk</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->sum(function($cat) { return $cat->products->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
