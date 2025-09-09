@extends('partials.layouts.main')

@section('page-title', 'Detail User - ' . $user->name)

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('manage-accounts.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                    ← Kembali
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white mb-1">Detail User - {{ $user->name }}</h1>
                    <p class="text-neutral-400">Informasi lengkap dan aktivitas user</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                @if(in_array($currentUser->role->name, ['owner', 'admin']))
                    <a href="{{ route('manage-accounts.edit', $user->id) }}" 
                       class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                        Edit User
                    </a>
                @endif
                
                @if($activeSession && in_array($currentUser->role->name, ['owner', 'manager', 'admin']))
                    <form method="POST" action="{{ route('manage-accounts.force-logout', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Force logout {{ $user->name }}?')"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                            Force Logout
                        </button>
                    </form>
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

    {{-- User Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Basic Info --}}
        <div class="lg:col-span-2">
            <div class="bg-neutral-800 rounded-xl border border-neutral-700">
                <div class="px-6 py-4 border-b border-neutral-700">
                    <h2 class="text-lg font-semibold text-white">Informasi User</h2>
                </div>
                
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- User Details --}}
                        <div class="flex-1 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Nama Lengkap</label>
                                    <p class="text-white font-medium">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Username</label>
                                    <p class="text-white">{{ $user->username }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Email</label>
                                    <p class="text-white">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Role/Jabatan</label>
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                        @if($user->role->name === 'owner') bg-purple-600/20 text-purple-400
                                        @elseif($user->role->name === 'admin') bg-blue-600/20 text-blue-400
                                        @elseif($user->role->name === 'manager') bg-green-600/20 text-green-400
                                        @elseif($user->role->name === 'kasir') bg-yellow-600/20 text-yellow-400
                                        @elseif($user->role->name === 'waiter') bg-cyan-600/20 text-cyan-400
                                        @elseif($user->role->name === 'chef') bg-red-600/20 text-red-400
                                        @else bg-neutral-600/20 text-neutral-400 @endif">
                                        {{ $user->role->display_name }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Status Akun</label>
                                    @if($user->is_active)
                                        <span class="flex items-center text-green-400">
                                            <i class="fas fa-circle text-xs mr-2"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="flex items-center text-red-400">
                                            <i class="fas fa-circle text-xs mr-2"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Status Online</label>
                                    @if($activeSession)
                                        <span class="flex items-center text-green-400">
                                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                            Online
                                        </span>
                                    @else
                                        <span class="flex items-center text-neutral-500">
                                            <span class="w-2 h-2 bg-neutral-500 rounded-full mr-2"></span>
                                            Offline
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Additional Info --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-neutral-700">
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Tanggal Dibuat</label>
                                    <p class="text-white">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-neutral-400 mb-1">Terakhir Update</label>
                                    <p class="text-white">{{ $user->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if($user->last_login_at)
                                    <div>
                                        <label class="block text-sm text-neutral-400 mb-1">Login Terakhir</label>
                                        <p class="text-white">{{ $user->last_login_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    @if($user->last_login_ip)
                                        <div>
                                            <label class="block text-sm text-neutral-400 mb-1">IP Address</label>
                                            <p class="text-white font-mono">{{ $user->last_login_ip }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats & Actions --}}
        <div class="space-y-6">
            {{-- Role Permissions --}}
            <div class="bg-neutral-800 rounded-xl border border-neutral-700 p-4">
                <h3 class="text-white font-medium mb-3">Hak Akses</h3>
                <div class="space-y-2 text-sm">
                    @php
                        $permissions = [
                            'owner' => [
                                'Akses penuh ke semua fitur',
                                'Kelola semua user dan role',
                                'Lihat semua laporan keuangan',
                                'Akses pengaturan sistem',
                                'Backup dan restore data'
                            ],
                            'admin' => [
                                'Kelola user (kecuali owner)',
                                'Kelola menu dan inventori', 
                                'Laporan harian dan operasional',
                                'Shift management',
                                'Pengaturan dasar sistem'
                            ],
                            'manager' => [
                                'Lihat laporan harian',
                                'Monitor operasi restoran',
                                'Request perubahan user',
                                'Supervisi karyawan',
                                'Approve void/refund'
                            ],
                            'kasir' => [
                                'Akses Point of Sale (POS)',
                                'Proses transaksi penjualan',
                                'Lihat transaksi hari ini',
                                'Handle pembayaran'
                            ],
                            'waiter' => [
                                'Kelola meja dan pesanan',
                                'Input pesanan pelanggan',
                                'Update status pesanan',
                                'Lihat menu items'
                            ],
                            'chef' => [
                                'Kitchen display system',
                                'Update status masakan',
                                'Lihat pesanan masuk',
                                'Manage menu items'
                            ]
                        ];
                        
                        $userPermissions = $permissions[$user->role->name] ?? [];
                    @endphp
                    
                    @foreach($userPermissions as $permission)
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span class="text-neutral-300">{{ $permission }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Session Info --}}
            @if($activeSession)
                <div class="bg-green-600/10 border border-green-600/30 rounded-xl p-4">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></span>
                        <h3 class="text-green-400 font-medium">Sedang Online</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Login:</span>
                            <span class="text-white">{{ $activeSession->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-neutral-400">Last Activity:</span>
                            <span class="text-white">{{ $activeSession->last_activity_at->format('H:i') }}</span>
                        </div>
                        @if($activeSession->ip_address)
                            <div class="flex justify-between">
                                <span class="text-neutral-400">IP:</span>
                                <span class="text-white font-mono">{{ $activeSession->ip_address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            @if($currentUser->role->name === 'manager' && !in_array($currentUser->role->name, ['owner', 'admin']))
                <div class="bg-blue-600/10 border border-blue-600/30 rounded-xl p-4">
                    <h3 class="text-blue-400 font-medium mb-3">Request Perubahan</h3>
                    <p class="text-sm text-neutral-300 mb-3">
                        Sebagai Manager, Anda dapat mengajukan perubahan user kepada Admin.
                    </p>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Ajukan Perubahan
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Activity History --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Activities --}}
        <div class="bg-neutral-800 rounded-xl border border-neutral-700">
            <div class="px-6 py-4 border-b border-neutral-700">
                <h2 class="text-lg font-semibold text-white">Aktivitas Terbaru</h2>
            </div>
            
            <div class="max-h-96 overflow-y-auto">
                @forelse($recentActivities->take(15) as $activity)
                    <div class="px-6 py-4 border-b border-neutral-700/50 last:border-b-0">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                @if($activity->action === 'login')
                                    <i class="fas fa-sign-in-alt text-green-400"></i>
                                @elseif($activity->action === 'logout')
                                    <i class="fas fa-sign-out-alt text-orange-400"></i>
                                @elseif(str_contains($activity->action, 'create'))
                                    <i class="fas fa-plus text-blue-400"></i>
                                @elseif(str_contains($activity->action, 'update'))
                                    <i class="fas fa-edit text-orange-400"></i>
                                @elseif(str_contains($activity->action, 'delete'))
                                    <i class="fas fa-trash text-red-400"></i>
                                @else
                                    <i class="fas fa-circle text-neutral-400"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white text-sm">
                                    {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                    @if($activity->table_name)
                                        - {{ $activity->table_name }}
                                        @if($activity->record_id)
                                            #{{ $activity->record_id }}
                                        @endif
                                    @endif
                                </p>
                                <p class="text-neutral-400 text-xs">
                                    {{ $activity->created_at->format('d M Y, H:i:s') }}
                                    @if($activity->ip_address)
                                        • {{ $activity->ip_address }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-history text-2xl text-neutral-600 mb-2"></i>
                        <p class="text-neutral-400">Belum ada aktivitas tercatat</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Login Sessions History --}}
        <div class="bg-neutral-800 rounded-xl border border-neutral-700">
            <div class="px-6 py-4 border-b border-neutral-700">
                <h2 class="text-lg font-semibold text-white">Riwayat Login</h2>
            </div>
            
            <div class="max-h-96 overflow-y-auto">
                @forelse($user->userSessions->take(15) as $session)
                    <div class="px-6 py-4 border-b border-neutral-700/50 last:border-b-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    @if($session->is_active)
                                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                        <span class="text-green-400 text-sm font-medium">Active</span>
                                    @else
                                        <span class="w-2 h-2 bg-neutral-500 rounded-full"></span>
                                        <span class="text-neutral-500 text-sm">Ended</span>
                                    @endif
                                </div>
                                <p class="text-white text-sm mt-1">
                                    {{ $session->created_at->format('d M Y, H:i') }}
                                </p>
                                <p class="text-neutral-400 text-xs">
                                    @if($session->ip_address)
                                        {{ $session->ip_address }}
                                    @endif
                                    @if($session->user_agent)
                                        • {{ Str::limit($session->user_agent, 30) }}
                                    @endif
                                </p>
                            </div>
                            <div class="text-right">
                                @if($session->last_activity_at)
                                    <p class="text-neutral-400 text-xs">
                                        Last: {{ $session->last_activity_at->format('H:i') }}
                                    </p>
                                @endif
                                @if($session->is_active)
                                    <span class="text-green-400 text-xs">Online</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-desktop text-2xl text-neutral-600 mb-2"></i>
                        <p class="text-neutral-400">Belum ada riwayat login</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('script-body')
<script>
    // Auto refresh online status every 30 seconds
    setInterval(() => {
        // You can implement AJAX call here to refresh session status
        console.log('Refreshing session status...');
    }, 30000);
</script>
@endpush
@endsection
