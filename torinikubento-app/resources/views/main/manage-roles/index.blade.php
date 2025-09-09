@extends('partials.layouts.main')

@section('page-title', 'Kelola Role & Hak Akses')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Kelola Role & Hak Akses</h1>
                <p class="text-neutral-400">Manajemen role dan hak akses sistem</p>
            </div>
            @if(in_array($currentUser->role->name, ['owner', 'admin']))
                <a href="{{ route('roles.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus text-sm"></i>
                    <span>Tambah Role Baru</span>
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
        {{-- Total Roles --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-white">{{ $stats['total_roles'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">Total Role</p>
            </div>
        </div>

        {{-- Active Roles --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-green-400">{{ $stats['active_roles'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">Role Aktif</p>
            </div>
        </div>

        {{-- Inactive Roles --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-red-400">{{ $stats['inactive_roles'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">Role Nonaktif</p>
            </div>
        </div>

        {{-- System Roles --}}
        <div class="bg-neutral-800 rounded-lg p-4 border border-neutral-700">
            <div class="text-center">
                <p class="text-2xl font-bold text-orange-400">{{ $stats['system_roles'] }}</p>
                <p class="text-neutral-400 text-sm mt-1">Role Sistem</p>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="mb-4">
        <div class="flex flex-wrap items-center gap-3">
            {{-- Search --}}
            <div class="flex-1 max-w-[440px]">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari nama role atau deskripsi..." 
                           class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg pl-10 pr-4 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none">
                    <i class="fas fa-search absolute left-3 top-3 text-neutral-400 text-sm"></i>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="min-w-40">
                <select id="statusFilter" class="w-full bg-neutral-900 hover:bg-neutral-700 text-white rounded-xl px-3 py-2 focus:outline-none text-sm">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>

            {{-- Type Filter --}}
            <div class="min-w-40">
                <select id="typeFilter" class="w-full bg-neutral-900 hover:bg-neutral-700 text-white rounded-xl px-3 py-2 focus:outline-none text-sm">
                    <option value="">Semua Tipe</option>
                    <option value="system">Role Sistem</option>
                    <option value="custom">Role Custom</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="bg-neutral-800 rounded-lg border border-neutral-700">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Hak Akses</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-neutral-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-700" id="rolesTableBody">
                    @forelse($roles as $role)
                        <tr class="hover:bg-neutral-750 transition-colors role-row" 
                            data-search="{{ strtolower($role->display_name . ' ' . $role->description) }}"
                            data-status="{{ $role->is_active ? 'active' : 'inactive' }}"
                            data-type="{{ in_array($role->name, ['owner', 'admin']) ? 'system' : 'custom' }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if(in_array($role->name, ['owner', 'admin']))
                                            <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-crown text-white text-xs"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-neutral-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-tag text-white text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-white">{{ $role->display_name }}</div>
                                        <div class="text-sm text-neutral-400">{{ $role->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-neutral-300">
                                    {{ $role->description ?: 'Tidak ada deskripsi' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-neutral-300">{{ count($role->permissions ?? []) }} hak akses</span>
                                    @if(count($role->permissions ?? []) > 0)
                                        <button type="button" class="text-blue-400 hover:text-blue-300 text-xs" 
                                                onclick="togglePermissions('{{ $role->id }}')">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                    @endif
                                </div>
                                {{-- Hidden permissions list --}}
                                <div id="permissions-{{ $role->id }}" class="hidden mt-2">
                                    <div class="bg-neutral-900 rounded p-2 text-xs">
                                        @if(count($role->permissions ?? []) > 0)
                                            @foreach($role->permissions as $permission)
                                                <span class="inline-block bg-neutral-700 text-neutral-300 px-2 py-1 rounded mr-1 mb-1">
                                                    {{ $permission }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-neutral-400">Tidak ada hak akses</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $role->is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                                    {{ $role->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-neutral-300">
                                    {{ $role->users_count }} user
                                    @if($role->users_count > 0)
                                        <a href="{{ route('roles.show', $role->id) }}" class="text-blue-400 hover:text-blue-300 ml-1">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if(in_array($role->name, ['owner', 'admin']))
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-orange-600/20 text-orange-400">
                                        Sistem
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-medium bg-blue-600/20 text-blue-400">
                                        Custom
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-3 text-sm">
                                    <a href="{{ route('roles.show', $role->id) }}" 
                                       class="text-blue-400 hover:text-blue-300">
                                        Detail
                                    </a>

                                    @if(in_array($currentUser->role->name, ['owner', 'admin']))
                                        {{-- Only owner can edit system roles --}}
                                        @if(!in_array($role->name, ['owner', 'admin']) || $currentUser->role->name === 'owner')
                                            <a href="{{ route('roles.edit', $role->id) }}" 
                                               class="text-orange-400 hover:text-orange-300">
                                                Edit
                                            </a>
                                        @endif

                                        {{-- Cannot delete system roles or roles in use --}}
                                        @if(!in_array($role->name, ['owner', 'admin']) && $role->users_count === 0)
                                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Hapus role {{ $role->display_name }}? Aksi ini tidak dapat dibatalkan.')"
                                                        class="text-red-400 hover:text-red-300">
                                                    Hapus
                                                </button>
                                            </form>
                                        @elseif($role->users_count > 0)
                                            <span class="text-neutral-500 cursor-not-allowed" title="Role tidak dapat dihapus karena masih digunakan">
                                                Hapus
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-neutral-400">
                                <i class="fas fa-user-tag text-4xl mb-4"></i>
                                <p class="text-lg">Belum ada data role</p>
                                <p class="text-sm">Tambahkan role pertama untuk mulai mengelola hak akses</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const roleRows = document.querySelectorAll('.role-row');

    function filterRoles() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        const typeValue = typeFilter.value;

        roleRows.forEach(row => {
            const searchText = row.dataset.search;
            const status = row.dataset.status;
            const type = row.dataset.type;

            const matchesSearch = searchText.includes(searchTerm);
            const matchesStatus = !statusValue || status === statusValue;
            const matchesType = !typeValue || type === typeValue;

            if (matchesSearch && matchesStatus && matchesType) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterRoles);
    statusFilter.addEventListener('change', filterRoles);
    typeFilter.addEventListener('change', filterRoles);
});

function togglePermissions(roleId) {
    const element = document.getElementById('permissions-' + roleId);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}
</script>
@endsection
