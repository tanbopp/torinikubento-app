@extends('partials.layouts.main')

@section('page-title', 'Tambah User Baru')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('manage-accounts.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                ← Kembali
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white mb-1">Tambah User Baru</h1>
                <p class="text-neutral-400">Buat akun karyawan baru dengan role dan hak akses</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-4">
            <div>
                <p class="font-medium">Terdapat kesalahan:</p>
                <ul class="mt-1 text-sm list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-3">
            <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                <div class="px-4 py-3 border-b border-neutral-700">
                    <h2 class="text-lg font-semibold text-white">Data User</h2>
                </div>
                
                <form method="POST" action="{{ route('manage-accounts.store') }}" class="p-4 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-neutral-300 mb-2">
                                Nama Lengkap <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('name') border-red-500 @enderror"
                                   placeholder="Masukkan nama lengkap"
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
                                   value="{{ old('username') }}"
                                   class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('username') border-red-500 @enderror"
                                   placeholder="Masukkan username"
                                   required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-300 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('email') border-red-500 @enderror"
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
                                class="w-full bg-neutral-700 text-white rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('role_id') border-red-500 @enderror"
                                required>
                            <option value="">Pilih role untuk user</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-neutral-300 mb-2">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none @error('password') border-red-500 @enderror"
                                   placeholder="Masukkan password"
                                   required>
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-neutral-300 mb-2">
                                Konfirmasi Password <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-3 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none"
                                   placeholder="Konfirmasi password"
                                   required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" 
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500">
                            <span class="text-sm text-neutral-300">Aktifkan akun setelah dibuat</span>
                        </label>
                        <p class="mt-1 text-xs text-neutral-400">User langsung bisa login jika dicentang</p>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-neutral-700">
                        <a href="{{ route('manage-accounts.index') }}" 
                           class="px-4 py-2 text-neutral-400 hover:text-white transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Buat User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Side Panel --}}
        <div class="space-y-4">
            {{-- Security Notice --}}
            <div class="bg-yellow-600/10 border border-yellow-600/30 rounded-lg p-4">
                <h3 class="text-yellow-400 font-medium mb-2">Keamanan</h3>
                <ul class="text-sm text-neutral-300 space-y-1">
                    <li>• Password minimal 6 karakter</li>
                    <li>• Username harus unik</li>
                    <li>• Email valid diperlukan</li>
                    <li>• Role menentukan akses sistem</li>
                </ul>
            </div>

            {{-- Role Permissions Preview --}}
            <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-4">
                <h3 class="text-white font-medium mb-3">Preview Hak Akses</h3>
                <div id="rolePermissions" class="text-sm text-neutral-400">
                    <p>Pilih role untuk melihat hak akses</p>
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
            'Akses pengaturan sistem'
        ],
        'admin': [
            'Kelola user (kecuali owner)',
            'Kelola menu dan inventori',
            'Laporan harian',
            'Shift management'
        ],
        'manager': [
            'Lihat laporan harian',
            'Monitor operasi restoran',
            'Supervisi karyawan',
            'Approve void/refund'
        ],
        'kasir': [
            'Akses Point of Sale (POS)',
            'Proses transaksi penjualan',
            'Handle pembayaran'
        ],
        'waiter': [
            'Kelola meja dan pesanan',
            'Input pesanan pelanggan',
            'Update status pesanan'
        ],
        'chef': [
            'Kitchen display system',
            'Update status masakan',
            'Manage menu items'
        ]
    };

    document.getElementById('role_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const roleText = selectedOption.text.toLowerCase();
        const roleKey = Object.keys(rolePermissions).find(key => roleText.includes(key));
        const permissionsDiv = document.getElementById('rolePermissions');
        
        if (roleKey && rolePermissions[roleKey]) {
            const permissions = rolePermissions[roleKey];
            permissionsDiv.innerHTML = `
                <div class="space-y-2">
                    ${permissions.map(permission => 
                        `<div class="flex items-center space-x-2">
                            <span class="w-1.5 h-1.5 bg-orange-400 rounded-full"></span>
                            <span class="text-neutral-300">${permission}</span>
                        </div>`
                    ).join('')}
                </div>
            `;
        } else {
            permissionsDiv.innerHTML = '<p>Pilih role untuk melihat hak akses</p>';
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
    });
</script>
@endpush
@endsection
