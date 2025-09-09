@extends('partials.layouts.main')

@section('page-title', 'Tambah Role Baru')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('roles.index') }}" class="text-neutral-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Tambah Role Baru</h1>
                <p class="text-neutral-400">Buat role baru dengan hak akses yang disesuaikan</p>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if($errors->any())
        <div class="bg-red-600/20 border border-red-600/30 text-red-400 px-4 py-3 rounded-lg mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('roles.store') }}" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Role Information --}}
            <div class="lg:col-span-1">
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Informasi Role</h3>
                    
                    {{-- Role Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-neutral-300 mb-2">Nama Role (System)</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" 
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none"
                               placeholder="contoh: kasir_senior" required>
                        <p class="text-xs text-neutral-400 mt-1">Hanya huruf, angka, dan underscore. Digunakan sistem internal.</p>
                    </div>

                    {{-- Display Name --}}
                    <div class="mb-4">
                        <label for="display_name" class="block text-sm font-medium text-neutral-300 mb-2">Nama Tampilan</label>
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}" 
                               class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none"
                               placeholder="Kasir Senior" required>
                        <p class="text-xs text-neutral-400 mt-1">Nama yang akan ditampilkan di interface.</p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-neutral-300 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full bg-neutral-700 text-white placeholder-neutral-400 rounded-lg px-4 py-2 border border-neutral-600 focus:border-orange-500 focus:outline-none"
                                  placeholder="Deskripsi singkat tentang role ini...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Is Active --}}
                    <div class="mb-4">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500 focus:ring-2">
                            <span class="text-sm text-neutral-300">Role Aktif</span>
                        </label>
                        <p class="text-xs text-neutral-400 mt-1">Role yang tidak aktif tidak dapat digunakan untuk user baru.</p>
                    </div>
                </div>
            </div>

            {{-- Permissions --}}
            <div class="lg:col-span-2">
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Hak Akses</h3>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="selectAllBtn" class="text-sm text-orange-400 hover:text-orange-300">
                                Pilih Semua
                            </button>
                            <span class="text-neutral-500">|</span>
                            <button type="button" id="deselectAllBtn" class="text-sm text-orange-400 hover:text-orange-300">
                                Batal Semua
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($availablePermissions as $category => $group)
                            <div class="border-l-4 border-orange-500 pl-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-white">{{ $group['label'] }}</h4>
                                    <button type="button" class="text-xs text-orange-400 hover:text-orange-300 category-toggle" 
                                            data-category="{{ $category }}">
                                        Pilih Semua
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    @foreach($group['permissions'] as $permission => $label)
                                        <label class="flex items-center space-x-3 p-2 rounded hover:bg-neutral-700 cursor-pointer">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}" 
                                                   {{ in_array($permission, old('permissions', [])) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-orange-600 bg-neutral-700 border-neutral-600 rounded focus:ring-orange-500 focus:ring-2 permission-checkbox"
                                                   data-category="{{ $category }}">
                                            <div>
                                                <span class="text-sm text-neutral-300">{{ $label }}</span>
                                                <div class="text-xs text-neutral-400">{{ $permission }}</div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Selected Permissions Counter --}}
                    <div class="mt-6 p-4 bg-neutral-900 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-400">Hak akses dipilih:</span>
                            <span id="selectedCount" class="text-sm font-medium text-orange-400">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-neutral-700">
            <a href="{{ route('roles.index') }}" class="px-6 py-2 bg-neutral-700 hover:bg-neutral-600 text-white rounded-lg transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors">
                Simpan Role
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const categoryToggles = document.querySelectorAll('.category-toggle');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    const selectedCount = document.getElementById('selectedCount');

    // Update selected count
    function updateSelectedCount() {
        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
    }

    // Select all permissions
    selectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(checkbox => checkbox.checked = true);
        updateSelectedCount();
    });

    // Deselect all permissions
    deselectAllBtn.addEventListener('click', function() {
        permissionCheckboxes.forEach(checkbox => checkbox.checked = false);
        updateSelectedCount();
    });

    // Category toggles
    categoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const category = this.dataset.category;
            const categoryCheckboxes = document.querySelectorAll(`[data-category="${category}"]`);
            const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
            
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            
            this.textContent = allChecked ? 'Pilih Semua' : 'Batal Semua';
            updateSelectedCount();
        });
    });

    // Update count when individual checkboxes change
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initial count
    updateSelectedCount();

    // Auto-generate system name from display name
    const displayNameInput = document.getElementById('display_name');
    const nameInput = document.getElementById('name');
    
    displayNameInput.addEventListener('input', function() {
        if (!nameInput.dataset.userModified) {
            const systemName = this.value
                .toLowerCase()
                .replace(/\s+/g, '_')
                .replace(/[^a-z0-9_]/g, '');
            nameInput.value = systemName;
        }
    });

    nameInput.addEventListener('input', function() {
        this.dataset.userModified = 'true';
    });
});
</script>
@endsection
