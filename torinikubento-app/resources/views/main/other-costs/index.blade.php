@extends('partials.layouts.main')

@section('page-title', 'Kelola Biaya Lain')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Kelola Biaya Lain</h1>
                <p class="text-neutral-400">Atur biaya tambahan yang digunakan dalam perhitungan harga pokok produk</p>
            </div>
            <div>
                <a href="{{ route('other-costs.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Biaya Lain</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            {{-- Search --}}
            <div class="flex-1 min-w-0">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau deskripsi biaya lain..."
                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>

            {{-- Type Filter --}}
            <select name="type" class="bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Semua Tipe</option>
                <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Persentase</option>
                <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Nominal Tetap</option>
            </select>

            {{-- Status Filter --}}
            <select name="status" class="bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <button type="submit" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <i class="fas fa-search text-sm"></i>
                <span>Filter</span>
            </button>

            @if(request()->hasAny(['search', 'type', 'status']))
                <a href="{{ route('other-costs.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-times text-sm"></i>
                    <span>Reset</span>
                </a>
            @endif
        </form>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-600 text-white p-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Other Costs Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-700">
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Nama</th>
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Tipe</th>
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Nilai</th>
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Deskripsi</th>
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Status</th>
                        <th class="text-left p-4 text-sm font-medium text-neutral-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($otherCosts as $otherCost)
                        <tr class="border-b border-neutral-700/50 hover:bg-neutral-700/30">
                            <td class="p-4">
                                <div class="font-medium text-white">{{ $otherCost->name }}</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $otherCost->type === 'percentage' ? 'bg-blue-600 text-blue-100' : 'bg-purple-600 text-purple-100' }}">
                                    {{ $otherCost->type === 'percentage' ? 'Persentase' : 'Nominal Tetap' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="text-white">
                                    @if($otherCost->type === 'percentage')
                                        {{ number_format($otherCost->value, 2) }}%
                                    @else
                                        Rp {{ number_format($otherCost->value, 0, ',', '.') }}
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="text-neutral-400 text-sm">
                                    {{ $otherCost->description ?: '-' }}
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $otherCost->is_active ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                    {{ $otherCost->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('other-costs.edit', $otherCost) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('other-costs.destroy', $otherCost) }}" 
                                          onsubmit="return confirm('Yakin ingin menghapus biaya lain ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-neutral-400">
                                <div class="space-y-2">
                                    <i class="fas fa-calculator text-3xl"></i>
                                    <p>Belum ada biaya lain yang didefinisikan</p>
                                    <a href="{{ route('other-costs.create') }}" class="text-orange-400 hover:text-orange-300">
                                        Tambahkan biaya lain pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($otherCosts->hasPages())
            <div class="px-6 py-4 border-t border-neutral-700">
                {{ $otherCosts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
