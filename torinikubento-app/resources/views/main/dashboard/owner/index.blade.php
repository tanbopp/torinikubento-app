@extends('partials.layouts.main')

@section('page-title', 'Dashboard')

@section('main-content')
<div class="p-6">
    {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Revenue --}}
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 rounded-xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm">Total Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold text-white">Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Orders Today --}}
            <div class="bg-neutral-800 rounded-xl px-6 py-4 border border-neutral-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-400 text-sm">Pesanan Hari Ini</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_orders_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            {{-- Customers Today --}}
            <div class="bg-neutral-800 rounded-xl px-6 py-4 border border-neutral-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-400 text-sm">Pelanggan Hari Ini</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_customers_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            {{-- Staff Count --}}
            <div class="bg-neutral-800 rounded-xl px-6 py-4 border border-neutral-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-neutral-400 text-sm">Total Staff</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_staff'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Recent Orders --}}
            <div class="bg-neutral-800 rounded-xl border border-neutral-700">
                <div class="px-6 py-4 border-b border-neutral-700">
                    <h2 class="text-lg font-semibold text-white">Pesanan Terbaru</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        {{-- Sample recent orders --}}
                        @for($i = 1; $i <= 5; $i++)
                        <div class="flex items-center justify-between py-3 border-b border-neutral-700/50 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <div class="bg-orange-600/20 rounded-full p-2">
                                    <svg class="w-4 h-4 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Order #{{ 100 + $i }}</p>
                                    <p class="text-neutral-400 text-sm">Meja {{ $i }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-medium">Rp {{ number_format(rand(50000, 200000), 0, ',', '.') }}</p>
                                <p class="text-orange-400 text-sm">{{ $i <= 2 ? 'Sedang diproses' : 'Selesai' }}</p>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Business Insights --}}
            <div class="bg-neutral-800 rounded-xl border border-neutral-700">
                <div class="px-6 py-4 border-b border-neutral-700">
                    <h2 class="text-lg font-semibold text-white">Ringkasan Bisnis</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        {{-- Monthly Revenue --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-600/20 rounded-full p-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Pendapatan Bulan Ini</p>
                                    <p class="text-neutral-400 text-sm">Target: Rp 150.000.000</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">Rp {{ number_format($stats['revenue_month'] ?? 0, 0, ',', '.') }}</p>
                                <p class="text-green-400 text-sm">+15%</p>
                            </div>
                        </div>

                        {{-- Menu Performance --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-600/20 rounded-full p-2">
                                    <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Total Menu</p>
                                    <p class="text-neutral-400 text-sm">Menu aktif</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">{{ $stats['total_menu'] ?? 0 }}</p>
                                <p class="text-neutral-400 text-sm">Items</p>
                            </div>
                        </div>

                        {{-- Staff Performance --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="bg-purple-600/20 rounded-full p-2">
                                    <svg class="w-4 h-4 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Staff Aktif</p>
                                    <p class="text-neutral-400 text-sm">Sedang bertugas</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">{{ $stats['total_staff'] - 2 ?? 8 }}</p>
                                <p class="text-green-400 text-sm">Online</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions Section --}}
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="/admin/dashboard" class="bg-neutral-800 hover:bg-neutral-700/50 rounded-xl px-6 py-4 border border-neutral-700 transition-colors group">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="text-white font-medium">Kelola Restoran</h3>
                        <p class="text-neutral-400 text-sm">Admin panel</p>
                    </div>
                </div>
            </a>

            <a href="/supervisor/dashboard" class="bg-neutral-800 hover:bg-neutral-700/50 rounded-xl px-6 py-4 border border-neutral-700 transition-colors group">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="text-white font-medium">Supervisi</h3>
                        <p class="text-neutral-400 text-sm">Monitor operasi</p>
                    </div>
                </div>
            </a>

            <a href="/inventory" class="bg-neutral-800 hover:bg-neutral-700/50 rounded-xl px-6 py-4 border border-neutral-700 transition-colors group">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="text-white font-medium">Inventori</h3>
                        <p class="text-neutral-400 text-sm">Kelola stok</p>
                    </div>
                </div>
            </a>

            <a href="#" class="bg-neutral-800 hover:bg-neutral-700/50 rounded-xl px-6 py-4 border border-neutral-700 transition-colors group">
                <div class="flex items-center space-x-4">
                    <div>
                        <h3 class="text-white font-medium">Laporan</h3>
                        <p class="text-neutral-400 text-sm">Analytics & reports</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('script-body')
<script>
    // Auto refresh stats setiap 30 detik
    setInterval(function() {
        // Bisa ditambahkan AJAX call untuk refresh stats
        console.log('Refreshing dashboard stats...');
    }, 30000);
</script>
@endpush
