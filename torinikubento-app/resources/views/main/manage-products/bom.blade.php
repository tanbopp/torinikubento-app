@extends('partials.layouts.main')

@section('main-content')
<div class="min-h-screen bg-neutral-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900">Resep & Bill of Materials (BOM)</h1>
            <p class="mt-2 text-neutral-600">Daftar resep dan komposisi bahan untuk setiap produk</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('products.bom') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama produk..."
                               class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="sm:w-48">
                        <select name="category_id" 
                                class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'category_id']))
                        <a href="{{ route('products.bom') }}" 
                           class="px-6 py-2 bg-neutral-600 text-white rounded-md hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 overflow-hidden">
                    <!-- Product Header -->
                    <div class="p-6 border-b border-neutral-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-neutral-900 mb-1">
                                    {{ $product->name }}
                                </h3>
                                @if($product->name_japanese)
                                    <p class="text-sm text-neutral-500 mb-2">{{ $product->name_japanese }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-sm text-neutral-600">
                                    <span class="bg-neutral-100 px-2 py-1 rounded-full">
                                        {{ $product->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                    @if($product->is_seasonal)
                                        <span class="bg-orange-100 text-orange-600 px-2 py-1 rounded-full">Seasonal</span>
                                    @endif
                                </div>
                            </div>
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-16 h-16 rounded-lg object-cover">
                            @endif
                        </div>
                    </div>

                    <!-- Recipe/BOM Content -->
                    <div class="p-6">
                        @if($product->ingredients->count() > 0)
                            <div class="space-y-3">
                                <h4 class="font-medium text-neutral-900 mb-3">Komposisi Bahan:</h4>
                                @foreach($product->ingredients as $bomItem)
                                    <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                        <div class="flex-1">
                                            <div class="font-medium text-neutral-900">
                                                {{ $bomItem->ingredient->name }}
                                            </div>
                                            @if($bomItem->ingredient->name_japanese)
                                                <div class="text-sm text-neutral-500">
                                                    {{ $bomItem->ingredient->name_japanese }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="font-medium text-neutral-900">
                                                {{ number_format($bomItem->quantity, 2) }} {{ $bomItem->ingredient->unit }}
                                            </div>
                                            @if($bomItem->ingredient->cost_per_unit)
                                                <div class="text-sm text-neutral-500">
                                                    Rp {{ number_format($bomItem->quantity * $bomItem->ingredient->cost_per_unit, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Total Cost -->
                                @php
                                    $totalCost = $product->ingredients->sum(function($bomItem) {
                                        return $bomItem->quantity * ($bomItem->ingredient->cost_per_unit ?? 0);
                                    });
                                @endphp
                                @if($totalCost > 0)
                                    <div class="pt-3 mt-3 border-t border-neutral-200">
                                        <div class="flex justify-between items-center font-semibold text-neutral-900">
                                            <span>Total Biaya Bahan:</span>
                                            <span>Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-8 text-neutral-500">
                                <i class="fas fa-flask text-2xl mb-2"></i>
                                <p class="font-medium">Belum ada resep</p>
                                <p class="text-sm">Produk ini belum memiliki komposisi bahan</p>
                            </div>
                        @endif
                    </div>

                    <!-- Notes -->
                    @if($product->notes)
                        <div class="px-6 pb-6">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-sticky-note text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-yellow-800">Catatan:</h4>
                                        <div class="mt-1 text-sm text-yellow-700">
                                            {{ $product->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12 bg-white rounded-lg shadow-sm border border-neutral-200">
                        <i class="fas fa-search text-4xl text-neutral-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-neutral-900 mb-2">Tidak ada produk ditemukan</h3>
                        <p class="text-neutral-500">
                            @if(request()->hasAny(['search', 'category_id']))
                                Coba ubah filter pencarian atau
                                <a href="{{ route('products.bom') }}" class="text-indigo-600 hover:text-indigo-500">reset filter</a>
                            @else
                                Belum ada produk yang tersedia di sistem
                            @endif
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form on category change
    document.querySelector('select[name="category_id"]').addEventListener('change', function() {
        this.closest('form').submit();
    });
</script>
@endpush
