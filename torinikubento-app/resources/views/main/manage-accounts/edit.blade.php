@extends('partials.layouts.main')

@section('page-title', 'Edit User - ' . $user->name)

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('manage-accounts.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                ← Kembali
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">Edit User - {{ $user->name }}</h1>
                <p class="text-neutral-400">Perbarui data dan hak akses user</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                <div>
                    <p class="font-medium">Terdapat kesalahan:</p>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <div class="bg-neutral-800 rounded-xl border border-neutral-700">
                <div class="px-6 py-4 border-b border-neutral-700">
                    <h2 class="text-lg font-semibold text-white">Data User</h2>
                </div>
                
                <form method="POST" action="{{ route('manage-accounts.update', $user->id) }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-neutral-300 mb-2">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-3 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('name') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap karyawan"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-neutral-300 mb-2">
                            Username <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               value="{{ old('username', $user->username) }}"
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-3 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('username') border-red-500 @enderror"
                               placeholder="Masukkan username untuk login"
                               required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-400">Username harus unik dan tidak boleh sama dengan user lain</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-3 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('email') border-red-500 @enderror"
                               placeholder="Masukkan alamat email"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-neutral-300 mb-2">
                            Role/Jabatan <span class="text-red-400">*</span>
                        </label>
                        <select id="role_id" 
                                name="role_id" 
                                class="w-full bg-neutral-700 text-white rounded-lg px-4 py-3 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('role_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih role untuk user</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }} ({{ ucfirst($role->name) }})
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500">
                                <span class="text-sm text-neutral-300">Akun aktif</span>
                            </label>
                            <p class="mt-1 text-xs text-neutral-400">Jika tidak dicentang, user tidak bisa login</p>
                        </div>

                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       name="reset_password" 
                                       value="1"
                                       class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500">
                                <span class="text-sm text-neutral-300">Reset password</span>
                            </label>
                            <p class="mt-1 text-xs text-neutral-400">
                                Password akan direset ke "password123" dan user diminta ganti password saat login
                            </p>
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-6 border-t border-neutral-700">
                        <a href="{{ route('manage-accounts.index') }}" 
                           class="px-4 py-2 text-neutral-400 hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Side Panel --}}
        <div class="space-y-6">
            {{-- User Info --}}
            <div class="bg-neutral-800 rounded-xl border border-neutral-700 p-4">
                <h3 class="text-white font-medium mb-3">Info User</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-400">User ID:</span>
                        <span class="text-white">#{{ $user->id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-400">Dibuat:</span>
                        <span class="text-white">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-400">Status:</span>
                        <span class="{{ $user->is_active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-400">Role Saat Ini:</span>
                        <span class="text-orange-400">{{ $user->role->display_name }}</span>
                    </div>
                    @if($user->last_login_at)
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-400">Last Login:</span>
                            <span class="text-white">{{ $user->last_login_at->format('d M Y, H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Current Role Permissions --}}
            <div class="bg-neutral-800 rounded-xl border border-neutral-700 p-4">
                <h3 class="text-white font-medium mb-3">Hak Akses Saat Ini</h3>
                <div id="currentRolePermissions" class="text-sm text-neutral-400">
                    {{-- Will be populated by JavaScript --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('script-body')
<script>
    // Role permissions data
    const rolePermissions = {
        'owner': [
            'Akses penuh ke semua fitur',
            'Kelola semua user dan role',
            'Lihat semua laporan keuangan',
            'Akses pengaturan sistem',
            'Backup dan restore data'
        ],
        'admin': [
            'Kelola user (kecuali owner)',
            'Kelola menu dan inventori',
            'Laporan harian dan operasional',
            'Shift management',
            'Pengaturan dasar sistem'
        ],
        'manager': [
            'Lihat laporan harian',
            'Monitor operasi restoran',
            'Request perubahan user',
            'Supervisi karyawan',
            'Approve void/refund'
        ],
        'kasir': [
            'Akses Point of Sale (POS)',
            'Proses transaksi penjualan',
            'Lihat transaksi hari ini',
            'Handle pembayaran'
        ],
        'waiter': [
            'Kelola meja dan pesanan',
            'Input pesanan pelanggan',
            'Update status pesanan',
            'Lihat menu items'
        ],
        'chef': [
            'Kitchen display system',
            'Update status masakan',
            'Lihat pesanan masuk',
            'Manage menu items'
        ]
    };

    // Show current role permissions
    function showRolePermissions(roleKey, targetId) {
        const targetDiv = document.getElementById(targetId);
        if (rolePermissions[roleKey]) {
            const permissions = rolePermissions[roleKey];
            targetDiv.innerHTML = `
                <div class="space-y-2">
                    ${permissions.map(permission => 
                        `<div class="flex items-center space-x-2">
                            <i class="fas fa-check text-green-400 text-xs"></i>
                            <span>${permission}</span>
                        </div>`
                    ).join('')}
                </div>
            `;
        } else {
            targetDiv.innerHTML = '<p>Tidak ada hak akses</p>';
        }
    }

    // Initialize current role permissions
    const currentRole = '{{ $user->role->name }}';
    showRolePermissions(currentRole, 'currentRolePermissions');

    // Handle role change
    document.getElementById('role_id').addEventListener('change', function() {
        const selectedRole = this.options[this.selectedIndex].text.toLowerCase();
        const roleKey = Object.keys(rolePermissions).find(key => selectedRole.includes(key));
        
        if (roleKey && roleKey !== currentRole) {
            showRolePermissions(roleKey, 'newRolePermissions');
        } else if (roleKey === currentRole) {
            document.getElementById('newRolePermissions').innerHTML = '<p class="text-neutral-500">Role tidak berubah</p>';
        } else {
            document.getElementById('newRolePermissions').innerHTML = '<p>Ubah role untuk melihat hak akses baru</p>';
        }
    });

    // Form validation and confirmation
    document.querySelector('form').addEventListener('submit', function(e) {
        const isActive = document.querySelector('input[name="is_active"]').checked;
        const resetPassword = document.querySelector('input[name="reset_password"]').checked;
        const selectedRole = document.getElementById('role_id');
        const newRoleName = selectedRole.options[selectedRole.selectedIndex].text;
        
        let confirmMessage = 'Konfirmasi perubahan:\n\n';
        
        if (selectedRole.value !== '{{ $user->role_id }}') {
            confirmMessage += `- Role akan diubah menjadi: ${newRoleName}\n`;
        }
        
        if (!isActive) {
            confirmMessage += '- Akun akan dinonaktifkan (user akan logout otomatis)\n';
        } else if (!{{ $user->is_active ? 'true' : 'false' }}) {
            confirmMessage += '- Akun akan diaktifkan kembali\n';
        }
        
        if (resetPassword) {
            confirmMessage += '- Password akan direset (user harus login ulang)\n';
        }
        
        confirmMessage += '\nLanjutkan perubahan?';
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
        }
    });
</script>
@endpush
@endsection
