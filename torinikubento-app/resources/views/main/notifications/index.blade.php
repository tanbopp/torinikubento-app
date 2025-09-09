@extends('partials.layouts.main')

@section('page-title', 'Notifikasi')

@section('main-content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Semua Notifikasi</h2>
            <p class="text-neutral-400">Kelola semua notifikasi sistem</p>
        </div>
        <div class="flex items-center space-x-4">
            <button class="bg-neutral-700 hover:bg-neutral-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-check-double mr-2"></i> Tandai Semua Terbaca
            </button>
            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-trash mr-2"></i> Hapus Semua
            </button>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex space-x-4 mb-6">
        <button class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Semua</button>
        <button class="bg-neutral-700 hover:bg-neutral-600 text-neutral-300 px-4 py-2 rounded-lg text-sm transition-colors">Belum Dibaca</button>
        <button class="bg-neutral-700 hover:bg-neutral-600 text-neutral-300 px-4 py-2 rounded-lg text-sm transition-colors">Sudah Dibaca</button>
        <button class="bg-neutral-700 hover:bg-neutral-600 text-neutral-300 px-4 py-2 rounded-lg text-sm transition-colors">Penting</button>
    </div>

    {{-- Notifications List --}}
    <div class="bg-neutral-800 rounded-xl border border-neutral-700">
        {{-- Sample notifications --}}
        <div class="p-4 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-white font-semibold">Pesanan Baru #001</h3>
                            <span class="bg-orange-600/20 text-orange-400 px-2 py-1 rounded text-xs font-medium">Pesanan</span>
                        </div>
                        <p class="text-neutral-300 text-sm mb-2">Pesanan baru dari Meja 5 - Ayam Teriyaki Bento x2, Salmon Bento x1</p>
                        <div class="flex items-center text-xs text-neutral-500 space-x-4">
                            <span><i class="fas fa-clock mr-1"></i> 2 menit yang lalu</span>
                            <span><i class="fas fa-map-marker-alt mr-1"></i> Meja 5</span>
                            <span><i class="fas fa-money-bill-wave mr-1"></i> Rp 125.000</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-neutral-400 hover:text-white p-1" title="Tandai sebagai terbaca">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="text-red-400 hover:text-red-300 p-1" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors opacity-70">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-3 h-3 bg-neutral-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-white font-semibold">Pembayaran Berhasil</h3>
                            <span class="bg-green-600/20 text-green-400 px-2 py-1 rounded text-xs font-medium">Pembayaran</span>
                        </div>
                        <p class="text-neutral-300 text-sm mb-2">Pembayaran pesanan #045 telah berhasil diproses</p>
                        <div class="flex items-center text-xs text-neutral-500 space-x-4">
                            <span><i class="fas fa-clock mr-1"></i> 5 menit yang lalu</span>
                            <span><i class="fas fa-credit-card mr-1"></i> QRIS</span>
                            <span><i class="fas fa-money-bill-wave mr-1"></i> Rp 85.000</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-neutral-400 hover:text-white p-1" title="Tandai sebagai belum dibaca">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button class="text-red-400 hover:text-red-300 p-1" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-3 h-3 bg-red-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-white font-semibold">Stok Rendah</h3>
                            <span class="bg-red-600/20 text-red-400 px-2 py-1 rounded text-xs font-medium">Penting</span>
                        </div>
                        <p class="text-neutral-300 text-sm mb-2">Stok Ayam Teriyaki tersisa 5 porsi. Segera lakukan restok!</p>
                        <div class="flex items-center text-xs text-neutral-500 space-x-4">
                            <span><i class="fas fa-clock mr-1"></i> 10 menit yang lalu</span>
                            <span><i class="fas fa-box mr-1"></i> Inventori</span>
                            <span><i class="fas fa-exclamation-triangle mr-1"></i> Stok Kritis</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-neutral-400 hover:text-white p-1" title="Tandai sebagai terbaca">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="text-red-400 hover:text-red-300 p-1" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors opacity-70">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-3 h-3 bg-neutral-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-white font-semibold">Shift Berakhir</h3>
                            <span class="bg-blue-600/20 text-blue-400 px-2 py-1 rounded text-xs font-medium">Sistem</span>
                        </div>
                        <p class="text-neutral-300 text-sm mb-2">Shift sore telah berakhir. Total pesanan: 45, Pendapatan: Rp 2.350.000</p>
                        <div class="flex items-center text-xs text-neutral-500 space-x-4">
                            <span><i class="fas fa-clock mr-1"></i> 1 jam yang lalu</span>
                            <span><i class="fas fa-users mr-1"></i> Shift Sore</span>
                            <span><i class="fas fa-chart-line mr-1"></i> +15% dari kemarin</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-neutral-400 hover:text-white p-1" title="Tandai sebagai belum dibaca">
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button class="text-red-400 hover:text-red-300 p-1" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="p-4 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-white font-semibold">Staff Login</h3>
                            <span class="bg-purple-600/20 text-purple-400 px-2 py-1 rounded text-xs font-medium">Staff</span>
                        </div>
                        <p class="text-neutral-300 text-sm mb-2">Andi (Kasir) telah login dan memulai shift malam</p>
                        <div class="flex items-center text-xs text-neutral-500 space-x-4">
                            <span><i class="fas fa-clock mr-1"></i> 2 jam yang lalu</span>
                            <span><i class="fas fa-id-badge mr-1"></i> Kasir</span>
                            <span><i class="fas fa-sign-in-alt mr-1"></i> Shift Malam</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="text-neutral-400 hover:text-white p-1" title="Tandai sebagai terbaca">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="text-red-400 hover:text-red-300 p-1" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Load More Button --}}
        <div class="p-4 text-center">
            <button class="bg-neutral-700 hover:bg-neutral-600 text-white px-6 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-chevron-down mr-2"></i> Muat Lebih Banyak
            </button>
        </div>
    </div>

    {{-- Pagination Info --}}
    <div class="mt-6 text-center text-neutral-400 text-sm">
        Menampilkan 6 dari 23 notifikasi
    </div>
</div>
@endsection

@push('script-body')
<script>
    // Auto refresh notifikasi setiap 30 detik
    setInterval(function() {
        console.log('Refreshing notifications...');
        // Implementasi AJAX untuk refresh notifikasi
    }, 30000);

    // Mark as read functionality
    document.querySelectorAll('[title="Tandai sebagai terbaca"]').forEach(btn => {
        btn.addEventListener('click', function() {
            // Implementasi mark as read
            console.log('Mark as read');
        });
    });

    // Delete notification functionality
    document.querySelectorAll('[title="Hapus"]').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Yakin ingin menghapus notifikasi ini?')) {
                // Implementasi delete notification
                console.log('Delete notification');
            }
        });
    });
</script>
@endpush
