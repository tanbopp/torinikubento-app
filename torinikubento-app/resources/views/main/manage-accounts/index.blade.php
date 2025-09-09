@extends('partials.layouts.main')

@section('page-title', 'Kelola Akun User')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Kelola Akun User</h1>
                <p class="text-neutral-400">Manajemen akun karyawan dan hak akses sistem</p>
            </div>
            @if(in_array($currentUser->role->name, ['owner', 'admin']))
                <a href="{{ route('manage-accounts.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah User Baru</span>
                </a>
            @endif
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

    @if(session('info'))
        <div class="bg-blue-600/20 border border-blue-600/30 text-blue-400 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Users --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-white">{{ $stats['total_users'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">Total User</p>
            </div>
        </div>

        {{-- Active Users --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-400">{{ $stats['active_users'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">User Aktif</p>
            </div>
        </div>

        {{-- Inactive Users --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-red-400">{{ $stats['inactive_users'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">User Nonaktif</p>
            </div>
        </div>

        {{-- Online Users --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-400">{{ $stats['online_users'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">User Online</p>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="mb-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Search --}}
            <div class="flex-1 max-w-[440px]">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari nama, username, atau email..." 
                           class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg pl-10 pr-4 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none">
                    <i class="fas fa-search absolute left-3 top-3 text-neutral-400 text-sm"></i>
                </div>
            </div>

            {{-- Role Filter --}}
            <div class="min-w-40">
                <select id="roleFilter" class="w-full bg-neutral-900 hover:bg-neutral-700 text-white rounded-xl px-3 py-2 focus:outline-none text-sm">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="min-w-32">
                <select id="statusFilter" class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none text-sm">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                    <option value="online">Online</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div>
        <div class="px-4 py-3">
            <h2 class="text-lg font-semibold text-white">Daftar User</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full" id="usersTable">
                <thead>
                    <tr class="text-left text-neutral-200 text-sm border-b border-neutral-800">
                        <th class="px-4 py-2 font-medium">User</th>
                        <th class="px-4 py-2 font-medium">Role</th>
                        <th class="px-4 py-2 font-medium">Status</th>
                        <th class="px-4 py-2 font-medium">Last Login</th>
                        <th class="px-4 py-2 font-medium">Online</th>
                        <th class="px-4 py-2 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-700/30">
                    @forelse($users as $user)
                        <tr class="hover:bg-neutral-700/30 user-row" 
                            data-role="{{ $user->role->name }}" 
                            data-status="{{ $user->is_active ? 'active' : 'inactive' }}"
                            data-online="{{ $user->userSessions->where('is_active', true)->where('last_activity_at', '>=', now()->subMinutes(5))->count() > 0 ? 'online' : 'offline' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="w-7 h-7 bg-orange-600 flex-shrink-0 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-white text-sm font-medium">{{ $user->name }}</p>
                                        <p class="text-neutral-400 text-xs">{{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-neutral-700 text-neutral-300">
                                    {{ $user->role->display_name }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $user->is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->last_login_at)
                                    <span class="text-neutral-300 text-sm">{{ $user->last_login_at->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="text-neutral-300/50 text-sm">Belum pernah</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $isOnline = $user->userSessions->where('is_active', true)->where('last_activity_at', '>=', now()->subMinutes(5))->count() > 0;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-green-400 animate-pulse' : 'bg-neutral-500' }}"></span>
                                    <span class="text-sm text-neutral-400">{{ $isOnline ? 'Online' : 'Offline' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3 text-sm">
                                    <a href="{{ route('manage-accounts.show', $user->id) }}" 
                                       class="text-blue-400 hover:text-blue-300">
                                        Detail
                                    </a>

                                    @if(in_array($currentUser->role->name, ['owner', 'admin']))
                                        <a href="{{ route('manage-accounts.edit', $user->id) }}" 
                                           class="text-orange-400 hover:text-orange-300">
                                            Edit
                                        </a>
                                    @endif

                                    @if($isOnline && in_array($currentUser->role->name, ['owner', 'manager', 'admin']))
                                        <form method="POST" action="{{ route('manage-accounts.force-logout', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    onclick="return confirm('Force logout {{ $user->name }}?')"
                                                    class="text-yellow-400 hover:text-yellow-300">
                                                Logout
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($currentUser->role->name, ['owner', 'admin']) && $user->id !== $currentUser->id)
                                        @if($user->is_active)
                                            <form method="POST" action="{{ route('manage-accounts.deactivate', $user->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Nonaktifkan {{ $user->name }}?')"
                                                        class="text-red-400 hover:text-red-300">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('manage-accounts.reactivate', $user->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Aktifkan {{ $user->name }}?')"
                                                        class="text-green-400 hover:text-green-300">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- Delete (Owner only) --}}
                                    @if($currentUser->role->name === 'owner' && $user->id !== $currentUser->id)
                                        <form method="POST" action="{{ route('manage-accounts.destroy', $user->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('HAPUS PERMANEN user {{ $user->name }}? Data tidak bisa dikembalikan!')"
                                                    class="text-red-600 hover:text-red-500 transition-colors" 
                                                    title="Hapus User">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-neutral-400">
                                <p>Belum ada user dalam sistem</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('script-body')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('roleFilter').addEventListener('change', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);

    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const roleFilter = document.getElementById('roleFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.user-row');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const userRole = row.dataset.role;
            const userStatus = row.dataset.status;
            const userOnline = row.dataset.online;

            let showRow = true;

            // Search filter
            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }

            // Role filter
            if (roleFilter && userRole !== roleFilter) {
                showRow = false;
            }

            // Status filter
            if (statusFilter) {
                if (statusFilter === 'active' && userStatus !== 'active') {
                    showRow = false;
                } else if (statusFilter === 'inactive' && userStatus !== 'inactive') {
                    showRow = false;
                } else if (statusFilter === 'online' && userOnline !== 'online') {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // Auto refresh online status every 30 seconds
    setInterval(() => {
        // You can implement AJAX call here to refresh online status
        console.log('Refreshing online status...');
    }, 30000);

    // Bulk force logout function
    function bulkForceLogout() {
        if (confirm('Force logout semua user yang sedang online?')) {
            // Implementation for bulk force logout
            alert('Fitur bulk force logout akan diimplementasikan');
        }
    }
</script>
@endpush
@endsection
