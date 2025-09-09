@extends('partials.layouts.main')

@section('page-title', 'Detail Role')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('roles.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex-1">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white mb-2">{{ $role->display_name }}</h1>
                        <p class="text-neutral-400">{{ $role->description ?: 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded text-sm font-medium {{ $role->is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                            {{ $role->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        @if(in_array($role->name, ['owner', 'admin']))
                            <span class="px-3 py-1 rounded text-sm font-medium bg-orange-600/20 text-orange-400">
                                <i class="fas fa-crown mr-1"></i>
                                Role Sistem
                            </span>
                        @else
                            <span class="px-3 py-1 rounded text-sm font-medium bg-blue-600/20 text-blue-400">
                                <i class="fas fa-user-tag mr-1"></i>
                                Role Custom
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center space-x-4 mt-4">
                    @if(in_array($currentUser->role->name, ['owner', 'admin']))
                        {{-- Only owner can edit system roles --}}
                        @if(!in_array($role->name, ['owner', 'admin']) || $currentUser->role->name === 'owner')
                            <a href="{{ route('roles.edit', $role->id) }}" 
                               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                                <i class="fas fa-edit text-sm"></i>
                                <span>Edit Role</span>
                            </a>
                        @endif

                        {{-- Cannot delete system roles or roles in use --}}
                        @if(!in_array($role->name, ['owner', 'admin']) && $role->users->count() === 0)
                            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Hapus role {{ $role->display_name }}? Aksi ini tidak dapat dibatalkan.')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span>Hapus Role</span>
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Role Information --}}
        <div class="lg:col-span-1">
            <div class="space-y-6">
                {{-- Basic Info --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Informasi Role</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-neutral-400">Nama System:</label>
                            <p class="text-white font-mono">{{ $role->name }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-neutral-400">Nama Tampilan:</label>
                            <p class="text-white">{{ $role->display_name }}</p>
                        </div>
                        
                        @if($role->description)
                            <div>
                                <label class="text-sm text-neutral-400">Deskripsi:</label>
                                <p class="text-white">{{ $role->description }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="text-sm text-neutral-400">Status:</label>
                            <p class="text-white">
                                <span class="px-2 py-1 rounded text-sm {{ $role->is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                                    {{ $role->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-neutral-400">Dibuat:</label>
                            <p class="text-white">{{ $role->created_at->format('d F Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm text-neutral-400">Diperbarui:</label>
                            <p class="text-white">{{ $role->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Statistik</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-neutral-400">Total Hak Akses:</span>
                            <span class="text-white font-semibold">{{ count($role->permissions ?? []) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-neutral-400">User Menggunakan:</span>
                            <span class="text-white font-semibold">{{ $role->users->count() }}</span>
                        </div>
                        
                        @if($role->users->count() > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-400">User Aktif:</span>
                                <span class="text-white font-semibold">{{ $role->users->where('is_active', true)->count() }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions and Users --}}
        <div class="lg:col-span-2">
            <div class="space-y-6">
                {{-- Permissions --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Hak Akses ({{ count($role->permissions ?? []) }})</h3>
                    
                    @if(count($role->permissions ?? []) > 0)
                        <div class="space-y-4">
                            @foreach($availablePermissions as $category => $group)
                                @php
                                    $categoryPermissions = array_intersect($role->permissions ?? [], array_keys($group['permissions']));
                                @endphp
                                @if(!empty($categoryPermissions))
                                    <div class="border-l-4 border-orange-500 pl-4">
                                        <h4 class="font-medium text-white mb-2">{{ $group['label'] }}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                            @foreach($categoryPermissions as $permission)
                                                <div class="flex items-center space-x-2 p-2 bg-neutral-900 rounded">
                                                    <i class="fas fa-check text-green-400 text-sm"></i>
                                                    <div>
                                                        <span class="text-sm text-white">{{ $group['permissions'][$permission] }}</span>
                                                        <div class="text-xs text-neutral-400">{{ $permission }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-lock text-4xl text-neutral-500 mb-4"></i>
                            <p class="text-neutral-400">Tidak ada hak akses yang diberikan</p>
                        </div>
                    @endif
                </div>

                {{-- Users with this role --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">User dengan Role Ini ({{ $role->users->count() }})</h3>
                    
                    @if($role->users->count() > 0)
                        <div class="space-y-3">
                            @foreach($role->users as $user)
                                <div class="flex items-center justify-between p-3 bg-neutral-900 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-white font-medium">{{ $user->name }}</p>
                                            <p class="text-sm text-neutral-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $user->is_active ? 'bg-green-600/20 text-green-400' : 'bg-red-600/20 text-red-400' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        @php
                                            $isOnline = $user->userSessions->where('is_active', true)->where('last_activity_at', '>=', now()->subMinutes(5))->count() > 0;
                                        @endphp
                                        <div class="flex items-center space-x-1">
                                            <span class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-green-400' : 'bg-neutral-500' }}"></span>
                                            <span class="text-xs text-neutral-400">{{ $isOnline ? 'Online' : 'Offline' }}</span>
                                        </div>
                                        <a href="{{ route('manage-accounts.show', $user->id) }}" 
                                           class="text-blue-400 hover:text-blue-300 text-sm">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-4xl text-neutral-500 mb-4"></i>
                            <p class="text-neutral-400 mb-2">Belum ada user dengan role ini</p>
                            <a href="{{ route('manage-accounts.create') }}?role={{ $role->id }}" 
                               class="text-orange-400 hover:text-orange-300 text-sm">
                                Tambah User Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
