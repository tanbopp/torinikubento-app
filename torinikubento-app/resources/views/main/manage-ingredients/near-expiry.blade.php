@extends('partials.layouts.main')

@section('page-title', 'Bahan Baku - Hampir Kadaluarsa')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">⏰ Bahan Baku Hampir Kadaluarsa</h1>
                <p class="text-neutral-400">Daftar bahan baku fresh yang akan kadaluarsa dalam 3 hari ke depan</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
                <a href="{{ route('ingredients.low-stock') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                    <span>Stok Rendah</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if($ingredients->count() > 0)
    <div class="bg-yellow-600/20 border border-yellow-600/30 text-yellow-400 px-4 py-3 rounded-lg mb-6">
        <div class="flex items-center">
            <i class="fas fa-clock mr-3"></i>
            <div>
                <p class="font-semibold">Perhatian!</p>
                <p class="text-sm">Ditemukan {{ $ingredients->total() }} bahan baku yang akan kadaluarsa dalam 3 hari ke depan. Segera gunakan atau buang untuk mencegah kontaminasi.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Near Expiry Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Bahan Baku Hampir Kadaluarsa</h3>
        </div>
        <div class="p-6">
            @if($ingredients->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b border-neutral-700">
                            <th class="pb-3 text-neutral-300 font-medium">Nama Bahan</th>
                            <th class="pb-3 text-neutral-300 font-medium">Stok Tersedia</th>
                            <th class="pb-3 text-neutral-300 font-medium">Entri Stok Kadaluarsa</th>
                            <th class="pb-3 text-neutral-300 font-medium">Masa Simpan</th>
                            <th class="pb-3 text-neutral-300 font-medium">Nilai Stok</th>
                            <th class="pb-3 text-neutral-300 font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach($ingredients as $ingredient)
                        <tr class="border-b border-neutral-700/50 hover:bg-neutral-700/30">
                            <td class="py-4">
                                <div>
                                    <p class="font-semibold text-white">{{ $ingredient->name }}</p>
                                    @if($ingredient->name_japanese)
                                    <p class="text-sm text-blue-400">{{ $ingredient->name_japanese }}</p>
                                    @endif
                                    <div class="flex items-center space-x-2 mt-1">
                                        <i class="fas fa-leaf text-green-400"></i>
                                        <span class="text-xs text-green-400">Bahan Segar</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-600/20 text-yellow-400">
                                            <i class="fas fa-clock mr-1"></i>
                                            Hampir Expired
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-center">
                                    <p class="text-lg font-bold text-white">{{ number_format($ingredient->current_stock, 2) }}</p>
                                    <p class="text-xs text-neutral-400">{{ $ingredient->unit }}</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="space-y-2">
                                    @foreach($ingredient->stockEntries as $stockEntry)
                                        @if($stockEntry->expiry_date && $stockEntry->expiry_date <= now()->addDays(3) && $stockEntry->quantity > 0)
                                        <div class="bg-neutral-700 rounded p-2">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold text-white">{{ number_format($stockEntry->quantity, 2) }} {{ $ingredient->unit }}</p>
                                                    <p class="text-xs text-neutral-400">Exp: {{ $stockEntry->expiry_date->format('d M Y') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    @php
                                                        $daysLeft = now()->diffInDays($stockEntry->expiry_date, false);
                                                    @endphp
                                                    @if($daysLeft < 0)
                                                        <span class="text-xs px-2 py-1 bg-red-600 text-white rounded">Kadaluarsa</span>
                                                    @elseif($daysLeft == 0)
                                                        <span class="text-xs px-2 py-1 bg-red-500 text-white rounded">Hari ini</span>
                                                    @elseif($daysLeft == 1)
                                                        <span class="text-xs px-2 py-1 bg-orange-500 text-white rounded">Besok</span>
                                                    @else
                                                        <span class="text-xs px-2 py-1 bg-yellow-500 text-white rounded">{{ $daysLeft }} hari</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-center">
                                    @if($ingredient->shelf_life_days)
                                        <p class="text-sm text-white">{{ $ingredient->shelf_life_days }} hari</p>
                                        <p class="text-xs text-neutral-400">masa simpan</p>
                                    @else
                                        <p class="text-xs text-neutral-400">Tidak terbatas</p>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-white">Rp {{ number_format($ingredient->current_stock * $ingredient->cost_per_unit, 0, ',', '.') }}</p>
                                    <p class="text-neutral-400">total nilai</p>
                                </div>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center space-x-2">
                                    @if(Auth::user()->role->hasPermission('view_ingredients'))
                                    <a href="{{ route('ingredients.show', $ingredient) }}" 
                                       class="text-blue-400 hover:text-blue-300" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endif
                                    @if(Auth::user()->role->hasPermission('edit_ingredients'))
                                    <a href="{{ route('ingredients.edit', $ingredient) }}" 
                                       class="text-yellow-400 hover:text-yellow-300" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
                    Menampilkan {{ $ingredients->firstItem() ?? 0 }} sampai {{ $ingredients->lastItem() ?? 0 }} 
                    dari {{ $ingredients->total() }} bahan baku hampir kadaluarsa
                </div>
                <div class="flex items-center space-x-2">
                    {{ $ingredients->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-12">
                <i class="fas fa-check-circle text-6xl text-green-600 mb-4"></i>
                <p class="text-xl text-neutral-400 mb-4">Semua bahan baku masih segar</p>
                <p class="text-neutral-500 mb-6">Tidak ada bahan baku segar yang akan kadaluarsa dalam 3 hari ke depan</p>
                <a href="{{ route('ingredients.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar Bahan Baku</span>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Summary Statistics --}}
    @if($ingredients->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-600/20 rounded-lg">
                    <i class="fas fa-clock text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Hampir Kadaluarsa</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $ingredients->total() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-red-600/20 rounded-lg">
                    <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Kadaluarsa Hari Ini</p>
                    @php
                        $todayCount = $ingredients->filter(function($ingredient) {
                            return $ingredient->stockEntries->where('expiry_date', '<=', now()->endOfDay())
                                                           ->where('expiry_date', '>=', now()->startOfDay())
                                                           ->where('quantity', '>', 0)
                                                           ->count() > 0;
                        })->count();
                    @endphp
                    <p class="text-2xl font-bold text-red-400">{{ $todayCount }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-orange-600/20 rounded-lg">
                    <i class="fas fa-dollar-sign text-orange-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Nilai Terancam</p>
                    <p class="text-2xl font-bold text-orange-400">
                        Rp {{ number_format($ingredients->sum(function($ing) { return $ing->current_stock * $ing->cost_per_unit; }), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-neutral-800 rounded-lg p-6 border border-neutral-700">
            <div class="flex items-center">
                <div class="p-2 bg-green-600/20 rounded-lg">
                    <i class="fas fa-leaf text-green-400 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-neutral-400">Total Bahan Segar</p>
                    <p class="text-2xl font-bold text-green-400">
                        {{ App\Models\Ingredient::where('type', 'fresh')->where('is_active', true)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Usage Recommendations --}}
    @if($ingredients->count() > 0)
    <div class="mt-6 bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="p-6 border-b border-neutral-700">
            <h3 class="text-lg font-semibold text-white">Rekomendasi Penggunaan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-yellow-600/10 rounded-lg p-4 border border-yellow-600/20">
                    <h4 class="font-semibold text-yellow-400 mb-3">⚡ Prioritas Tinggi - Gunakan Segera</h4>
                    <ul class="space-y-2 text-sm text-neutral-300">
                        @foreach($ingredients->take(3) as $ingredient)
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-circle text-red-400 text-xs"></i>
                            <span>{{ $ingredient->name }} - {{ number_format($ingredient->current_stock, 1) }} {{ $ingredient->unit }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="bg-blue-600/10 rounded-lg p-4 border border-blue-600/20">
                    <h4 class="font-semibold text-blue-400 mb-3">💡 Tips Manajemen Stok Segar</h4>
                    <ul class="space-y-2 text-sm text-neutral-300">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-lightbulb text-blue-400 text-xs mt-1"></i>
                            <span>Gunakan sistem FIFO (First In, First Out) untuk bahan segar</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-lightbulb text-blue-400 text-xs mt-1"></i>
                            <span>Cek kondisi fisik bahan secara berkala</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-lightbulb text-blue-400 text-xs mt-1"></i>
                            <span>Pertimbangkan untuk membuat menu khusus dengan bahan yang akan expired</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
