@extends('partials.layouts.main')

@section('page-title', 'Manajemen Produk')

@push('styles')
<style>
.sort-arrows {
    display: flex;
    flex-direction: column;
    margin-left: 4px;
}
.sort-arrow {
    height: 12px;
    width: 12px;
    transition: color 0.2s ease;
}
.sort-arrow.active {
    color: #ea580c; /* orange-600 */
}
.sort-arrow.inactive {
    color: #525252; /* neutral-600 */
}
.sortable-header {
    user-select: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.sortable-header:hover .sort-arrow.inactive {
    color: #737373; /* neutral-500 */
}
.sortable-header.active-sort {
    color: #f3f4f6; /* neutral-100 */
}
.sortable-header.active-sort .sort-arrow.inactive {
    color: #6b7280; /* neutral-500 */
}
</style>
@endpush

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

    {{-- Filters, Sort and Search --}}
    <div class="mb-6">
        <form method="GET" action="{{ route('products.index') }}">
            <div>
                <div class="flex flex-col lg:flex-row gap-3 justify-between">
                    {{-- Search --}}
                    <div class="flex-1 lg:max-w-[300px]">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari terkait produk..."
                                   class="w-full bg-neutral-800 text-white placeholder-neutral-400 rounded-lg pl-10 pr-3 py-1.5 ring-1 ring-neutral-600 focus:ring-[3px] focus:ring-orange-500/80 outline-none transition-al2 text-sm">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400 text-sm"></i>
                        </div>
                    </div>

                    <div class="flex gap-x-1">
                        {{-- Sort Reset Button --}}
                        @if(request()->has('sort') || request()->has('direction'))
                        <a href="{{ route('products.index', array_diff_key(request()->all(), ['sort' => '', 'direction' => ''])) }}" 
                           class="aspect-square h-8 flex justify-center items-center hover:bg-neutral-700 text-white rounded-lg transition-colors text-sm font-medium whitespace-nowrap relative group" 
                           title="Reset urutan">
                            <svg class="h-4 text-orange-400 transition-colors group-hover:text-orange-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 8H21C21.6 8 22 7.6 22 7C22 6.4 21.6 6 21 6H3C2.4 6 2 6.4 2 7C2 7.6 2.4 8 3 8Z" fill="currentColor"/>
                            <path d="M7 16H17C17.6 16 18 16.4 18 17C18 17.6 17.6 18 17 18H7C6.4 18 6 17.6 6 17C6 16.4 6.4 16 7 16Z" fill="currentColor"/>
                            <path d="M4.8 11H19.2C19.68 11 20 11.4 20 12C20 12.6 19.68 13 19.2 13H4.8C4.32 13 4 12.6 4 12C4 11.4 4.32 11 4.8 11Z" fill="currentColor"/>
                            </svg>
                            {{-- Tooltip --}}
                            <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-neutral-900 text-white text-xs rounded px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-10">
                                Reset urutan
                            </div>
                        </a>
                        @endif

                        {{-- Filter Menu --}}
                        <div class="flex gap-2">
                            {{-- Filter Context Menu --}}
                            <x-ui.filter-menu 
                                id="product-filters" 
                                width="w-72"
                                :hasActiveFilters="request()->hasAny(['category_id', 'status', 'availability', 'seasonal'])"
                                filterTitle="Filter Produk"
                                filterSubtitle="Pilih kategori dan status produk"
                                :clearUrl="route('products.index')">
                                {{-- Category Filter --}}
                                <div class="px-4 py-3">
                                    <label class="block text-sm font-medium text-white mb-2">Kategori</label>
                                    <select name="category_id" class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 text-sm border border-neutral-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="border-t border-neutral-700/30 my-1"></div>

                                {{-- Status Filter --}}
                                <div class="px-4 py-3">
                                    <label class="block text-sm font-medium text-white mb-2">Status Produk</label>
                                    <select name="status" class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 text-sm border border-neutral-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">
                                        <option value="">Semua Status</option>
                                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                </div>

                                {{-- Availability Filter --}}
                                <div class="px-4 py-3">
                                    <label class="block text-sm font-medium text-white mb-2">Ketersediaan</label>
                                    <select name="availability" class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 text-sm border border-neutral-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">
                                        <option value="">Semua</option>
                                        <option value="1" {{ request('availability') === '1' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="0" {{ request('availability') === '0' ? 'selected' : '' }}>Habis</option>
                                    </select>
                                </div>

                                {{-- Seasonal Filter --}}
                                <div class="px-4 py-3">
                                    <label class="block text-sm font-medium text-white mb-2">Tipe</label>
                                    <select name="seasonal" class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 text-sm border border-neutral-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none">
                                        <option value="">Semua Tipe</option>
                                        <option value="1" {{ request('seasonal') === '1' ? 'selected' : '' }}>Musiman</option>
                                        <option value="0" {{ request('seasonal') === '0' ? 'selected' : '' }}>Reguler</option>
                                    </select>
                                </div>
                            </x-ui.filter-menu>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div>
        @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full" id="productsTable">
                <thead>
                    <tr class="text-left text-neutral-200 text-sm border-b border-neutral-800">
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'name' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Produk</span>
                            </a>
                        </th>
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'category_id', 'direction' => request('sort') === 'category_id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'category_id' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Kategori</span>
                            </a>
                        </th>
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'base_price', 'direction' => request('sort') === 'base_price' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'base_price' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Harga</span>
                            </a>
                        </th>
                        @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'cost_price', 'direction' => request('sort') === 'cost_price' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'cost_price' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Biaya/Margin</span>
                            </a>
                        </th>
                        @endif
                        @if($columnVisibility['has_inactive_products'] || $columnVisibility['has_unavailable_products'])
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'is_active', 'direction' => request('sort') === 'is_active' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'is_active' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Status</span>
                            </a>
                        </th>
                        @endif
                        @if($columnVisibility['has_seasonal_products'] || $columnVisibility['has_limited_products'] || $columnVisibility['has_daily_limits'])
                        <th class="px-4 py-2">
                            <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'is_seasonal', 'direction' => request('sort') === 'is_seasonal' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center text-neutral-400 hover:text-white transition-colors font-normal">
                                <svg class="w-4 h-4 mr-2 {{ request('sort') === 'is_seasonal' ? 'text-orange-500' : 'text-neutral-500' }}" 
                                     viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.71 21.71L18.71 17.71C18.8032 17.6167 18.8772 17.5061 18.9277 17.3842C18.9781 17.2624 19.0041 17.1318 19.0041 17C19.0041 16.7337 18.8983 16.4783 18.71 16.29C18.5217 16.1017 18.2663 15.9959 18 15.9959C17.7337 15.9959 17.4783 16.1017 17.29 16.29L15 18.59V6.99999C15 6.73477 14.8946 6.48042 14.7071 6.29288C14.5196 6.10534 14.2652 5.99999 14 5.99999C13.7348 5.99999 13.4804 6.10534 13.2929 6.29288C13.1054 6.48042 13 6.73477 13 6.99999L13 21C13.001 21.1974 13.0604 21.3901 13.1707 21.5538C13.2811 21.7176 13.4374 21.845 13.62 21.92C13.8021 21.9966 14.0028 22.0175 14.1968 21.9801C14.3908 21.9427 14.5694 21.8487 14.71 21.71ZM11 17L11 2.99999C10.999 2.80256 10.9396 2.60985 10.8293 2.44613C10.7189 2.2824 10.5626 2.15501 10.38 2.07999C10.1979 2.00341 9.99717 1.98248 9.80318 2.01986C9.60919 2.05723 9.43062 2.15123 9.29 2.28999L5.29 6.28999C5.19627 6.38295 5.12187 6.49355 5.07111 6.61541C5.02034 6.73727 4.9942 6.86798 4.9942 6.99999C4.9942 7.132 5.02034 7.2627 5.07111 7.38456C5.12187 7.50642 5.19627 7.61702 5.29 7.70999C5.38296 7.80372 5.49356 7.87811 5.61542 7.92888C5.73728 7.97965 5.86799 8.00579 6 8.00579C6.13201 8.00579 6.26272 7.97965 6.38457 7.92888C6.50643 7.87811 6.61703 7.80372 6.71 7.70999L9 5.40999L9 17C9 17.2652 9.10535 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17Z" 
                                          fill="currentColor"/>
                                </svg>
                                <span>Tipe</span>
                            </a>
                        </th>
                        @endif
                        <th class="px-4 py-2 font-normal text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-700/30">
                    @foreach($products as $product)
                    <tr class="hover:bg-neutral-700/20 product-row cursor-pointer" 
                        data-category="{{ $product->category->name }}"
                        data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                        data-availability="{{ $product->is_available ? 'available' : 'unavailable' }}"
                        onclick="window.location.href='{{ route('products.show', $product) }}'">
                        <td class="px-4 py-1.5">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                    <div class="w-10 h-10 bg-neutral-700 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-utensils text-neutral-400 text-sm"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-white text-sm font-medium truncate">{{ $product->name }}</p>
                                    @if($columnVisibility['has_japanese_names'] && $product->name_japanese)
                                    <p class="text-xs text-neutral-400 truncate">{{ $product->name_japanese }}</p>
                                    @endif
                                    @if($columnVisibility['has_spice_levels'] && $product->spice_level > 0)
                                    <p class="text-xs text-neutral-400">
                                        🌶️ Level {{ $product->spice_level }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-1.5">
                            <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-800 text-neutral-300">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td class="px-4 py-1.5">
                            <div>
                                @if($columnVisibility['has_promo_prices'] && $product->promo_price && $product->promo_price < $product->base_price)
                                    <p class="text-xs text-neutral-400 line-through">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm font-semibold text-white">
                                        Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-sm font-semibold text-white">
                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                    </p>
                                @endif
                                @if($columnVisibility['has_taxes'] && $product->tax && $product->tax->is_active)
                                    <p class="text-xs text-neutral-400">
                                        +Pajak: Rp {{ number_format($product->getPriceWithTax(), 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </td>
                        @if(Auth::user()->role->hasPermission('view_cost_analysis'))
                        <td class="px-4 py-1.5">
                            <div>
                                <p class="text-xs text-neutral-400">HPP: Rp {{ number_format($product->cost_price, 0, ',', '.') }}</p>
                                <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-800 text-neutral-300">
                                    {{ number_format($product->margin_percentage, 1) }}% margin
                                </span>
                            </div>
                        </td>
                        @endif
                        @if($columnVisibility['has_inactive_products'] || $columnVisibility['has_unavailable_products'])
                        <td class="px-4 py-1.5">
                            <div class="flex flex-col space-y-1">
                                @if($columnVisibility['has_inactive_products'])
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $product->is_active ? 'bg-neutral-800 text-white' : 'bg-neutral-800 text-neutral-300' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @endif
                                @if($columnVisibility['has_unavailable_products'])
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $product->is_available ? 'bg-neutral-800 text-white' : 'bg-neutral-800 text-neutral-300' }}">
                                    {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                                </span>
                                @endif
                            </div>
                        </td>
                        @endif
                        @if($columnVisibility['has_seasonal_products'] || $columnVisibility['has_limited_products'] || $columnVisibility['has_daily_limits'])
                        <td class="px-4 py-1.5">
                            <div class="flex flex-col space-y-1">
                                @if($columnVisibility['has_seasonal_products'] && $product->is_seasonal)
                                <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-800 text-neutral-300">
                                    Musiman
                                </span>
                                @endif
                                @if($columnVisibility['has_limited_products'] && $product->is_limited_edition)
                                <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-800 text-neutral-300">
                                    Limited
                                </span>
                                @endif
                                @if($columnVisibility['has_daily_limits'] && $product->daily_limit)
                                <p class="text-xs text-neutral-400">Max: {{ $product->daily_limit }}/hari</p>
                                @endif
                            </div>
                        </td>
                        @endif
                        <td class="px-4 py-1.5" onclick="event.stopPropagation()">
                            {{-- Context Menu using Component --}}
                            <x-ui.context-menu id="product-menu-{{ $product->id }}">
                                {{-- View Details --}}
                                @if(Auth::user()->role->hasPermission('view_products'))
                                <x-ui.context-menu.item href="{{ route('products.show', $product) }}">
                                    <svg class="h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21.92 11.6C19.9 6.91 16.1 4 12 4C7.90001 4 4.10001 6.91 2.08001 11.6C2.02494 11.7262 1.99652 11.8623 1.99652 12C1.99652 12.1377 2.02494 12.2738 2.08001 12.4C4.10001 17.09 7.90001 20 12 20C16.1 20 19.9 17.09 21.92 12.4C21.9751 12.2738 22.0035 12.1377 22.0035 12C22.0035 11.8623 21.9751 11.7262 21.92 11.6V11.6ZM12 18C8.83001 18 5.83001 15.71 4.10001 12C5.83001 8.29 8.83001 6 12 6C15.17 6 18.17 8.29 19.9 12C18.17 15.71 15.17 18 12 18ZM12 8C11.2089 8 10.4355 8.2346 9.77773 8.67412C9.11993 9.11365 8.60724 9.73836 8.30449 10.4693C8.00174 11.2002 7.92252 12.0044 8.07686 12.7804C8.23121 13.5563 8.61217 14.269 9.17158 14.8284C9.73099 15.3878 10.4437 15.7688 11.2196 15.9231C11.9956 16.0775 12.7998 15.9983 13.5307 15.6955C14.2616 15.3928 14.8864 14.8801 15.3259 14.2223C15.7654 13.5645 16 12.7911 16 12C16 10.9391 15.5786 9.92172 14.8284 9.17157C14.0783 8.42143 13.0609 8 12 8V8ZM12 14C11.6044 14 11.2178 13.8827 10.8889 13.6629C10.56 13.4432 10.3036 13.1308 10.1522 12.7654C10.0009 12.3999 9.96126 11.9978 10.0384 11.6098C10.1156 11.2219 10.3061 10.8655 10.5858 10.5858C10.8655 10.3061 11.2219 10.1156 11.6098 10.0384C11.9978 9.96126 12.3999 10.0009 12.7654 10.1522C13.1308 10.3036 13.4432 10.56 13.6629 10.8889C13.8827 11.2178 14 11.6044 14 12C14 12.5304 13.7893 13.0391 13.4142 13.4142C13.0391 13.7893 12.5304 14 12 14Z" fill="currentColor"/>
                                    </svg>
                                    Lihat Detail
                                </x-ui.context-menu.item>
                                @endif

                                {{-- Edit --}}
                                @if(Auth::user()->role->hasPermission('edit_products'))
                                <x-ui.context-menu.item href="{{ route('products.edit', $product) }}">
                                    <svg class="h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 7.24002C22.0008 7.10841 21.9756 6.97795 21.9258 6.85611C21.876 6.73427 21.8027 6.62346 21.71 6.53002L17.47 2.29002C17.3766 2.19734 17.2658 2.12401 17.1439 2.07425C17.0221 2.02448 16.8916 1.99926 16.76 2.00002C16.6284 1.99926 16.4979 2.02448 16.3761 2.07425C16.2543 2.12401 16.1435 2.19734 16.05 2.29002L13.22 5.12002L2.29002 16.05C2.19734 16.1435 2.12401 16.2543 2.07425 16.3761C2.02448 16.4979 1.99926 16.6284 2.00002 16.76V21C2.00002 21.2652 2.10537 21.5196 2.29291 21.7071C2.48045 21.8947 2.7348 22 3.00002 22H7.24002C7.37994 22.0076 7.51991 21.9857 7.65084 21.9358C7.78176 21.8858 7.90073 21.8089 8.00002 21.71L18.87 10.78L21.71 8.00002C21.8013 7.9031 21.8757 7.79155 21.93 7.67002C21.9397 7.59031 21.9397 7.50973 21.93 7.43002C21.9347 7.38347 21.9347 7.33657 21.93 7.29002L22 7.24002ZM6.83002 20H4.00002V17.17L13.93 7.24002L16.76 10.07L6.83002 20ZM18.17 8.66002L15.34 5.83002L16.76 4.42002L19.58 7.24002L18.17 8.66002Z" fill="currentColor"/>
                                    </svg>
                                    Edit Produk
                                </x-ui.context-menu.item>

                                <x-ui.context-menu.divider />

                                {{-- Toggle Status --}}
                                <x-ui.context-menu.item 
                                    href="{{ route('products.toggle-status', $product) }}"
                                    method="PATCH"
                                    confirm="{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk {{ $product->name }}?"
                                    class="text-neutral-300">
                                    <svg class="h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="{{ $product->is_active ? 'M8.6699 4.22998C8.83178 4.27253 9.00175 4.27378 9.16424 4.23359C9.32672 4.19341 9.47652 4.11308 9.5999 3.99998H14.6999L13.4299 8.73998C13.3895 8.8883 13.3837 9.04395 13.4132 9.19484C13.4427 9.34573 13.5066 9.48779 13.5999 9.60998C13.6931 9.73099 13.8127 9.82906 13.9496 9.89666C14.0866 9.96427 14.2372 9.99961 14.3899 9.99998H17.9999L16.8699 11.24C16.6926 11.4364 16.6005 11.6952 16.6136 11.9594C16.6267 12.2237 16.7441 12.472 16.9399 12.65C17.1233 12.8167 17.3621 12.9093 17.6099 12.91C17.7494 12.9096 17.8874 12.8801 18.0148 12.8233C18.1423 12.7664 18.2564 12.6836 18.3499 12.58L20.9999 9.66998C21.1312 9.52465 21.2169 9.34397 21.2465 9.15036C21.276 8.95675 21.248 8.75873 21.166 8.58087C21.084 8.40301 20.9516 8.25315 20.7852 8.14989C20.6188 8.04662 20.4257 7.99449 20.2299 7.99998H15.6899L16.9999 3.25998C17.039 3.1109 17.043 2.95481 17.0118 2.80389C16.9806 2.65297 16.9149 2.51132 16.8199 2.38998C16.7237 2.26488 16.5992 2.16432 16.4567 2.09654C16.3142 2.02876 16.1577 1.99567 15.9999 1.99998H8.9999C8.77414 1.99224 8.55241 2.06118 8.37082 2.19555C8.18924 2.32992 8.0585 2.52181 7.9999 2.73998V2.99998C7.92826 3.2519 7.95831 3.52187 8.08359 3.75187C8.20888 3.98187 8.4194 4.15354 8.6699 4.22998ZM21.6699 20.29L3.6699 2.28998C3.47529 2.1481 3.23601 2.08123 2.99604 2.10164C2.75607 2.12206 2.53153 2.2284 2.36368 2.40111C2.19583 2.57382 2.09595 2.80131 2.0824 3.04177C2.06884 3.28222 2.14252 3.51949 2.2899 3.70998L6.6099 7.99998L5.3499 12.74C5.31084 12.8891 5.30676 13.0451 5.33798 13.1961C5.36921 13.347 5.43489 13.4886 5.5299 13.61C5.62306 13.731 5.7427 13.8291 5.87964 13.8967C6.01658 13.9643 6.16718 13.9996 6.3199 14H10.1599L8.3499 20.74C8.29128 20.9574 8.30777 21.1883 8.3967 21.3952C8.48563 21.6021 8.64179 21.7729 8.8399 21.88C8.98732 21.9595 9.15238 22.0008 9.3199 22C9.45944 21.9996 9.59738 21.9701 9.72482 21.9133C9.85227 21.8564 9.96641 21.7736 10.0599 21.67L14.9099 16.33L20.2899 21.71C20.3829 21.8037 20.4935 21.8781 20.6153 21.9289C20.7372 21.9796 20.8679 22.0058 20.9999 22.0058C21.1319 22.0058 21.2626 21.9796 21.3845 21.9289C21.5063 21.8781 21.6169 21.8037 21.7099 21.71C21.8036 21.617 21.878 21.5064 21.9288 21.3846C21.9796 21.2627 22.0057 21.132 22.0057 21C22.0057 20.868 21.9796 20.7373 21.9288 20.6154C21.878 20.4935 21.8036 20.3829 21.7099 20.29H21.6699ZM7.6199 12L8.2499 9.65998L10.5899 12H7.6199ZM11.3499 17.28L12.3499 13.72L13.5499 14.91L11.3499 17.28Z' : 'M19.8701 8.6001C19.7956 8.42882 19.6746 8.28183 19.5209 8.17578C19.3671 8.06974 19.1867 8.00888 19.0001 8.0001H14.4201L15.6901 3.2601C15.7306 3.11177 15.7363 2.95612 15.7068 2.80523C15.6773 2.65434 15.6134 2.51229 15.5201 2.3901C15.427 2.26908 15.3073 2.17101 15.1704 2.10341C15.0334 2.03581 14.8828 2.00047 14.7301 2.0001H7.73012C7.50436 1.99236 7.28263 2.06118 7.10104 2.19567C6.91946 2.33004 6.78872 2.52194 6.73012 2.7401L4.05012 12.7401C4.00967 12.8884 4.00397 13.0441 4.03345 13.195C4.06294 13.3459 4.12681 13.4879 4.22012 13.6101C4.31431 13.7325 4.43555 13.8313 4.57434 13.899C4.71314 13.9667 4.86572 14.0013 5.02012 14.0001H8.89012L7.08012 20.7401C7.02082 20.9574 7.03664 21.1884 7.12502 21.3957C7.21341 21.6029 7.36917 21.7742 7.56708 21.8818C7.76499 21.9895 7.99344 22.0271 8.21543 21.9887C8.43741 21.9503 8.63991 21.838 8.79012 21.6701L19.6901 9.6701C19.8199 9.52798 19.9059 9.35143 19.9379 9.16163C19.9698 8.97184 19.9463 8.77686 19.8701 8.6001ZM10.0801 17.2801L11.1501 13.2801C11.1906 13.1318 11.1963 12.9761 11.1668 12.8252C11.1373 12.6743 11.0734 12.5323 10.9801 12.4101C10.887 12.2891 10.7673 12.191 10.6304 12.1234C10.4934 12.0558 10.3428 12.0205 10.1901 12.0201H6.35012L8.49012 4.0001H13.4201L12.1501 8.7401C12.1093 8.89115 12.1046 9.04967 12.1362 9.2029C12.1679 9.35613 12.235 9.4998 12.3323 9.62235C12.4295 9.74491 12.5542 9.84293 12.6963 9.90854C12.8383 9.97414 12.9938 10.0055 13.1501 10.0001H16.7201L10.0801 17.2801Z' }}" fill="currentColor"/>
                                    </svg>
                                    {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </x-ui.context-menu.item>

                                {{-- Toggle Availability --}}
                                <x-ui.context-menu.item 
                                    href="{{ route('products.toggle-availability', $product) }}"
                                    method="PATCH"
                                    confirm="{{ $product->is_available ? 'Tandai habis' : 'Tandai tersedia' }} produk {{ $product->name }}?"
                                    class="text-neutral-300">
                                    <svg class="h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM13 17H11V15H13V17ZM13 13H11V7H13V13Z" fill="currentColor"/>
                                    </svg>
                                    {{ $product->is_available ? 'Tandai Habis' : 'Tandai Tersedia' }}
                                </x-ui.context-menu.item>
                                @endif

                                {{-- Delete --}}
                                @if(Auth::user()->role->hasPermission('delete_products'))
                                <x-ui.context-menu.divider />
                                <x-ui.context-menu.item 
                                    href="{{ route('products.destroy', $product) }}"
                                    method="DELETE"
                                    confirm="Hapus produk {{ $product->name }}? Aksi ini tidak dapat dibatalkan!"
                                    class="text-red-400">
                                    <svg class="h-5 mr-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 18C10.2652 18 10.5196 17.8946 10.7071 17.7071C10.8946 17.5196 11 17.2652 11 17V11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11V17C9 17.2652 9.10536 17.5196 9.29289 17.7071C9.48043 17.8946 9.73478 18 10 18ZM20 6H16V5C16 4.20435 15.6839 3.44129 15.1213 2.87868C14.5587 2.31607 13.7956 2 13 2H11C10.2044 2 9.44129 2.31607 8.87868 2.87868C8.31607 3.44129 8 4.20435 8 5V6H4C3.73478 6 3.48043 6.10536 3.29289 6.29289C3.10536 6.48043 3 6.73478 3 7C3 7.26522 3.10536 7.51957 3.29289 7.70711C3.48043 7.89464 3.73478 8 4 8H5V19C5 19.7956 5.31607 20.5587 5.87868 21.1213C6.44129 21.6839 7.20435 22 8 22H16C16.7956 22 17.5587 21.6839 18.1213 21.1213C18.6839 20.5587 19 19.7956 19 19V8H20C20.2652 8 20.5196 7.89464 20.7071 7.70711C20.8946 7.51957 21 7.26522 21 7C21 6.73478 20.8946 6.48043 20.7071 6.29289C20.5196 6.10536 20.2652 6 20 6ZM10 5C10 4.73478 10.1054 4.48043 10.2929 4.29289C10.4804 4.10536 10.7348 4 11 4H13C13.2652 4 13.5196 4.10536 13.7071 4.29289C13.8946 4.48043 14 4.73478 14 5V6H10V5ZM17 19C17 19.2652 16.8946 19.5196 16.7071 19.7071C16.5196 19.8946 16.2652 20 16 20H8C7.73478 20 7.48043 19.8946 7.29289 19.7071C7.10536 19.5196 7 19.2652 7 19V8H17V19ZM14 18C14.2652 18 14.5196 17.8946 14.7071 17.7071C14.8946 17.5196 15 17.2652 15 17V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10C13.7348 10 13.4804 10.1054 13.2929 10.2929C13.1054 10.4804 13 10.7348 13 11V17C13 17.2652 13.1054 17.5196 13.2929 17.7071C13.4804 17.8946 13.7348 18 14 18Z" fill="currentColor"/>
                                    </svg>
                                    Hapus Produk
                                </x-ui.context-menu.item>
                                @endif
                            </x-ui.context-menu>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
        {{-- Pagination --}}
        <div class="flex items-center justify-between mt-6 px-4">
            <div class="text-sm text-neutral-400">
                <div>
                    Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} 
                    dari {{ $products->total() }} produk
                </div>
                @if(request()->has('sort') && request('sort') !== 'sort_order')
                <div class="mt-1 text-xs">
                    @php
                        $sortLabels = [
                            'name' => 'Produk',
                            'category_id' => 'Kategori', 
                            'base_price' => 'Harga',
                            'cost_price' => 'Biaya',
                            'is_active' => 'Status',
                            'is_seasonal' => 'Tipe'
                        ];
                        $sortLabel = $sortLabels[request('sort')] ?? request('sort');
                        $directionLabel = request('direction') === 'desc' ? 'Z-A' : 'A-Z';
                        if(in_array(request('sort'), ['base_price', 'cost_price'])) {
                            $directionLabel = request('direction') === 'desc' ? 'Tinggi-Rendah' : 'Rendah-Tinggi';
                        }
                    @endphp
                    <span class="text-orange-400">Urutan: {{ $sortLabel }} ({{ $directionLabel }})</span>
                </div>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                {{ $products->appends(request()->query())->links() }}
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
@endsection
