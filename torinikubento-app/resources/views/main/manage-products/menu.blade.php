@extends('partials.layouts.main')

@section('page-title', 'Menu Analytics')

@section('main-content')
<div class="p-6">
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

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-end justify-between mt-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Menu Analytics</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('products.index') }}" class="hover:bg-neutral-700/50 text-neutral-100 font-medium px-3 py-2 border border-neutral-700 active:bg-neutral-700 rounded-xl transition-colors flex items-center space-x-2 text-sm">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Produk</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-3">
                <div class="text-xs text-neutral-400 mb-1">Total Produk</div>
                <div class="text-xl font-bold text-white">{{ $products->count() }}</div>
            </div>
            <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-3">
                <div class="text-xs text-neutral-400 mb-1">Rata-rata Margin</div>
                <div class="text-xl font-bold text-blue-400">{{ number_format($products->avg('margin_percentage'), 1) }}%</div>
            </div>
            <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-3">
                <div class="text-xs text-neutral-400 mb-1">Rata-rata Popularitas</div>
                <div class="text-xl font-bold text-green-400">{{ number_format($products->avg('popularity_score'), 1) }}</div>
            </div>
            <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-3">
                <div class="text-xs text-neutral-400 mb-1">Total Harga Jual</div>
                <div class="text-lg font-bold text-yellow-400">Rp {{ number_format($products->sum('base_price'), 0, ',', '.') }}</div>
            </div>
            <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-3">
                <div class="text-xs text-neutral-400 mb-1">Total Harga Pokok</div>
                <div class="text-lg font-bold text-red-400">Rp {{ number_format($products->sum('cost_price'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-grow-1">
                    <h6 class="text-sm font-medium text-neutral-300 mb-1">Stars</h6>
                    <h3 class="text-2xl font-bold text-green-400 mb-1">{{ $products->where('classification', 'Star')->count() }}</h3>
                    <small class="text-xs text-neutral-400">Profit Tinggi & Populer</small>
                </div>
                <div class="ml-3">
                    <i class="fas fa-star text-2xl text-green-400 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-grow-1">
                    <h6 class="text-sm font-medium text-neutral-300 mb-1">Puzzles</h6>
                    <h3 class="text-2xl font-bold text-yellow-400 mb-1">{{ $products->where('classification', 'Puzzle')->count() }}</h3>
                    <small class="text-xs text-neutral-400">Profit Tinggi & Kurang Populer</small>
                </div>
                <div class="ml-3">
                    <i class="fas fa-puzzle-piece text-2xl text-yellow-400 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-grow-1">
                    <h6 class="text-sm font-medium text-neutral-300 mb-1">Plowhorses</h6>
                    <h3 class="text-2xl font-bold text-blue-400 mb-1">{{ $products->where('classification', 'Plowhorse')->count() }}</h3>
                    <small class="text-xs text-neutral-400">Profit Rendah & Populer</small>
                </div>
                <div class="ml-3">
                    <i class="fas fa-chart-line text-2xl text-blue-400 opacity-75"></i>
                </div>
            </div>
        </div>
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg p-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-grow-1">
                    <h6 class="text-sm font-medium text-neutral-300 mb-1">Dogs</h6>
                    <h3 class="text-2xl font-bold text-red-400 mb-1">{{ $products->where('classification', 'Dog')->count() }}</h3>
                    <small class="text-xs text-neutral-400">Profit Rendah & Kurang Populer</small>
                </div>
                <div class="ml-3">
                    <i class="fas fa-times-circle text-2xl text-red-400 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategy Guide -->
    <div class="bg-neutral-800 border border-neutral-700 rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-neutral-700">
            <h6 class="text-white font-medium">Panduan Strategi Menu</h6>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <h6 class="text-green-400 font-medium mb-2">Stars</h6>
                    <ul class="text-sm text-neutral-300 space-y-1">
                        <li>• Promosikan secara intensif</li>
                        <li>• Tempatkan di posisi menu terlihat</li>
                        <li>• Latih staff untuk merekomendasikan</li>
                        <li>• Jaga kualitas secara konsisten</li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-yellow-400 font-medium mb-2">Puzzles</h6>
                    <ul class="text-sm text-neutral-300 space-y-1">
                        <li>• Tingkatkan marketing</li>
                        <li>• Pindahkan posisi di menu</li>
                        <li>• Tambahkan deskripsi menarik</li>
                        <li>• Pertimbangkan penurunan harga</li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-blue-400 font-medium mb-2">Plowhorses</h6>
                    <ul class="text-sm text-neutral-300 space-y-1">
                        <li>• Kurangi biaya porsi</li>
                        <li>• Cari bahan baku lebih murah</li>
                        <li>• Naikkan harga secara hati-hati</li>
                        <li>• Pindah ke lokasi menu biaya rendah</li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-red-400 font-medium mb-2">Dogs</h6>
                    <ul class="text-sm text-neutral-300 space-y-1">
                        <li>• Pertimbangkan hapus dari menu</li>
                        <li>• Atau revisi resep total</li>
                        <li>• Sembunyikan dari posisi utama</li>
                        <li>• Jangan promosikan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Analysis -->
    <div class="bg-neutral-800 border border-neutral-700 rounded-lg mb-6">
        <div class="px-4 py-3 border-b border-neutral-700">
            <h6 class="text-white font-medium">Detail Analisis Produk</h6>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-neutral-300 text-sm border-b border-neutral-700">
                            <th class="px-4 py-3 font-medium">Produk</th>
                            <th class="px-4 py-3 font-medium">Kategori</th>
                            <th class="px-4 py-3 font-medium">Harga Jual</th>
                            <th class="px-4 py-3 font-medium">Harga Pokok</th>
                            <th class="px-4 py-3 font-medium">Margin %</th>
                            <th class="px-4 py-3 font-medium">Skor Popularitas</th>
                            <th class="px-4 py-3 font-medium">Klasifikasi</th>
                            <th class="px-4 py-3 font-medium">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700/30">
                        @foreach($products->sortByDesc('margin_percentage')->sortByDesc('popularity_score') as $product)
                        <tr class="hover:bg-neutral-700/20">
                            <td class="px-4 py-3">
                                <div>
                                    <p class="text-white font-medium">{{ $product->name }}</p>
                                    @if($product->name_japanese)
                                    <p class="text-xs text-blue-400 mt-1">{{ $product->name_japanese }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-700 text-neutral-300">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-white">Rp {{ number_format($product->base_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-white">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    {{ $product->margin_percentage > 30 ? 'bg-green-600 text-white' : 
                                       ($product->margin_percentage > 15 ? 'bg-yellow-600 text-white' : 'bg-red-600 text-white') }}">
                                    {{ number_format($product->margin_percentage, 1) }}%
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-full bg-neutral-700 rounded-full h-5">
                                    <div class="h-5 rounded-full flex items-center justify-center text-xs font-medium
                                        {{ $product->popularity_score > 50 ? 'bg-green-600 text-white' : 'bg-neutral-600 text-neutral-300' }}" 
                                         style="width: {{ min($product->popularity_score, 100) }}%">
                                        {{ $product->popularity_score }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    @switch($product->classification)
                                        @case('Star') bg-green-600 text-white @break
                                        @case('Puzzle') bg-yellow-600 text-white @break
                                        @case('Plowhorse') bg-blue-600 text-white @break
                                        @case('Dog') bg-red-600 text-white @break
                                    @endswitch">
                                    {{ $product->classification }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-neutral-400">
                                    @switch($product->classification)
                                        @case('Star')
                                            Promosikan & jaga kualitas
                                            @break
                                        @case('Puzzle')
                                            Tingkatkan usaha marketing
                                            @break
                                        @case('Plowhorse')
                                            Kurangi biaya atau naikkan harga
                                            @break
                                        @case('Dog')
                                            Pertimbangkan hapus dari menu
                                            @break
                                    @endswitch
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg">
            <div class="px-4 py-3 border-b border-neutral-700">
                <h6 class="text-white font-medium">Distribusi Klasifikasi Menu</h6>
            </div>
            <div class="p-4">
                <canvas id="classificationChart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="bg-neutral-800 border border-neutral-700 rounded-lg">
            <div class="px-4 py-3 border-b border-neutral-700">
                <h6 class="text-white font-medium">Matrix Profit vs Popularitas</h6>
            </div>
            <div class="p-4">
                <canvas id="matrixChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Classification Distribution Chart
    const classificationData = {
        labels: ['Stars', 'Puzzles', 'Plowhorses', 'Dogs'],
        datasets: [{
            data: [
                {{ $products->where('classification', 'Star')->count() }},
                {{ $products->where('classification', 'Puzzle')->count() }},
                {{ $products->where('classification', 'Plowhorse')->count() }},
                {{ $products->where('classification', 'Dog')->count() }}
            ],
            backgroundColor: [
                '#22c55e', // green-500
                '#eab308', // yellow-500
                '#3b82f6', // blue-500
                '#ef4444'  // red-500
            ],
            borderWidth: 2,
            borderColor: '#374151' // neutral-700
        }]
    };

    new Chart(document.getElementById('classificationChart'), {
        type: 'doughnut',
        data: classificationData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#d1d5db' // neutral-300
                    }
                }
            }
        }
    });

    // Matrix Scatter Plot
    const matrixData = {
        datasets: [{
            label: 'Products',
            data: [
                @foreach($products as $product)
                {
                    x: {{ $product->popularity_score }},
                    y: {{ $product->margin_percentage }},
                    label: '{{ $product->name }}',
                    backgroundColor: 
                        @switch($product->classification)
                            @case('Star') '#22c55e' @break
                            @case('Puzzle') '#eab308' @break
                            @case('Plowhorse') '#3b82f6' @break
                            @case('Dog') '#ef4444' @break
                        @endswitch
                },
                @endforeach
            ],
            backgroundColor: function(context) {
                return context.parsed ? context.raw.backgroundColor : '#ccc';
            }
        }]
    };

    new Chart(document.getElementById('matrixChart'), {
        type: 'scatter',
        data: matrixData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Skor Popularitas',
                        color: '#d1d5db' // neutral-300
                    },
                    ticks: {
                        color: '#9ca3af' // neutral-400
                    },
                    grid: {
                        color: '#374151' // neutral-700
                    },
                    min: 0,
                    max: 100
                },
                y: {
                    title: {
                        display: true,
                        text: 'Margin Profit (%)',
                        color: '#d1d5db' // neutral-300
                    },
                    ticks: {
                        color: '#9ca3af' // neutral-400
                    },
                    grid: {
                        color: '#374151' // neutral-700
                    },
                    min: 0
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw.label + 
                                   ': Popularitas ' + context.parsed.x + 
                                   ', Margin ' + context.parsed.y.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
