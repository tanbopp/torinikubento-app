@extends('partials.layouts.main')

@section('page-title', 'Edit Biaya Lain')

@section('main-content')
<div class="p-6">
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">Edit Biaya Lain</h1>
                <p class="text-neutral-400">Perbarui informasi biaya lain: {{ $otherCost->name }}</p>
            </div>
            <div>
                <a href="{{ route('other-costs.index') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left text-sm"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('other-costs.update', $otherCost) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            {{-- Main Form --}}
            <div class="xl:col-span-2">
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Informasi Biaya Lain</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Nama Biaya <span class="text-red-400">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $otherCost->name) }}"
                                   placeholder="Contoh: Pajak PPN, Biaya Layanan, Biaya Kemasan"
                                   class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Tipe Biaya <span class="text-red-400">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center p-4 bg-neutral-700 border border-neutral-600 rounded-lg cursor-pointer hover:bg-neutral-600 transition-colors">
                                    <input type="radio" 
                                           name="type" 
                                           value="percentage" 
                                           {{ old('type', $otherCost->type) === 'percentage' ? 'checked' : '' }}
                                           class="mr-3 text-orange-500 focus:ring-orange-500">
                                    <div>
                                        <div class="text-white font-medium">Persentase (%)</div>
                                        <div class="text-xs text-neutral-400">Berdasarkan persentase dari harga bahan</div>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 bg-neutral-700 border border-neutral-600 rounded-lg cursor-pointer hover:bg-neutral-600 transition-colors">
                                    <input type="radio" 
                                           name="type" 
                                           value="fixed" 
                                           {{ old('type', $otherCost->type) === 'fixed' ? 'checked' : '' }}
                                           class="mr-3 text-orange-500 focus:ring-orange-500">
                                    <div>
                                        <div class="text-white font-medium">Nominal Tetap (Rp)</div>
                                        <div class="text-xs text-neutral-400">Biaya tetap dalam rupiah</div>
                                    </div>
                                </label>
                            </div>
                            @error('type')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Value --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Nilai <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <span id="currency-symbol" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-400">
                                    {{ $otherCost->type === 'percentage' ? '%' : 'Rp' }}
                                </span>
                                <input type="number" 
                                       name="value" 
                                       value="{{ old('value', $otherCost->value) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full bg-neutral-700 border border-neutral-600 rounded-lg pl-8 pr-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('value') border-red-500 @enderror">
                            </div>
                            @error('value')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p id="value-help" class="mt-1 text-xs text-neutral-400">
                                {{ $otherCost->type === 'percentage' ? 'Masukkan nilai persentase, contoh: 11 untuk 11%' : 'Masukkan nilai dalam rupiah, contoh: 2000 untuk Rp 2.000' }}
                            </p>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea name="description" 
                                      rows="3"
                                      placeholder="Deskripsi optional tentang biaya ini..."
                                      class="w-full bg-neutral-700 border border-neutral-600 rounded-lg px-3 py-2 text-white placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $otherCost->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $otherCost->is_active) ? 'checked' : '' }}
                                   class="mr-3 rounded text-orange-500 focus:ring-orange-500">
                            <label class="text-sm text-neutral-300">Aktif</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Current Info --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700">
                    <div class="p-6 border-b border-neutral-700">
                        <h3 class="text-lg font-semibold text-white">Info Saat Ini</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <div class="text-sm text-neutral-400">Nama</div>
                            <div class="text-white">{{ $otherCost->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-400">Tipe</div>
                            <div class="text-white">{{ $otherCost->type === 'percentage' ? 'Persentase' : 'Nominal Tetap' }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-400">Nilai</div>
                            <div class="text-white">
                                @if($otherCost->type === 'percentage')
                                    {{ number_format($otherCost->value, 2) }}%
                                @else
                                    Rp {{ number_format($otherCost->value, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-sm text-neutral-400">Status</div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $otherCost->is_active ? 'bg-green-600 text-green-100' : 'bg-red-600 text-red-100' }}">
                                    {{ $otherCost->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="bg-neutral-800 rounded-lg border border-neutral-700 p-6">
                    <div class="space-y-3">
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-save text-sm"></i>
                            <span>Update Biaya Lain</span>
                        </button>
                        
                        <a href="{{ route('other-costs.index') }}" class="w-full bg-neutral-600 hover:bg-neutral-700 text-white py-2 px-4 rounded-lg transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-times text-sm"></i>
                            <span>Batal</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const currencySymbol = document.getElementById('currency-symbol');
    const valueHelp = document.getElementById('value-help');
    const valueInput = document.querySelector('input[name="value"]');

    function updateValueField() {
        const selectedType = document.querySelector('input[name="type"]:checked');
        if (selectedType) {
            if (selectedType.value === 'percentage') {
                currencySymbol.textContent = '%';
                valueHelp.textContent = 'Masukkan nilai persentase, contoh: 11 untuk 11%';
            } else {
                currencySymbol.textContent = 'Rp';
                valueHelp.textContent = 'Masukkan nilai dalam rupiah, contoh: 2000 untuk Rp 2.000';
            }
        }
    }

    typeRadios.forEach(radio => {
        radio.addEventListener('change', updateValueField);
    });

    // Set initial state
    updateValueField();
});
</script>
@endsection
