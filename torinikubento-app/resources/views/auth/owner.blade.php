@extends('partials.layouts.auth')

@section('main-content')
<div class="h-screen flex flex-col">
    <div class="flex flex-col h-full w-full items-center justify-center">
        {{-- Header --}}
        <div class="text-center mb-8">
            <h2 class="text-5xl font-bold mb-6">Selamat Datang</h2>
            <p class="text-neutral-500 text-xl font-medium mb-6">Masuk dengan akun milik Anda</p>
        </div>

        {{-- Login Form --}}
        <div class="w-full max-w-md mx-auto">
            <form>
                {{-- Input Nomor --}}
                <div>
                    <input type="text" 
                           name="nomor" 
                           placeholder="Nomor" 
                           class="w-full px-4 py-3 bg-neutral-800/80 border border-neutral-600/50 rounded-2xl rounded-b-none text-white placeholder-neutral-500 focus:outline-none focus:ring-2 ring-inset focus:ring-orange-400 focus:border-transparent">
                </div>
                
                {{-- Input Password --}}
                <div>
                    <input type="password" 
                           name="password" 
                           placeholder="Password" 
                           class="w-full px-4 py-3 bg-neutral-800/80 border border-neutral-600/50 rounded-2xl rounded-t-none border-t-0 text-white placeholder-neutral-500 focus:outline-none focus:ring-2 ring-inset focus:ring-orange-400 focus:border-transparent">
                </div>
            </form>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-between items-center w-full max-w-md mx-auto mt-8">
            <button class="text-orange-500 font-medium flex items-center space-x-1 group">
                <svg class="h-[12px] mt-0.5 transform translate-x-0 group-hover:-translate-x-0.5 transition" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.29 6.00001L6.83 2.46001C7.01626 2.27265 7.1208 2.0192 7.1208 1.75501C7.1208 1.49082 7.01626 1.23737 6.83 1.05001C6.73704 0.956281 6.62644 0.881887 6.50458 0.831118C6.38272 0.780349 6.25202 0.754211 6.12 0.754211C5.98799 0.754211 5.85729 0.780349 5.73543 0.831118C5.61357 0.881887 5.50297 0.956281 5.41 1.05001L1.17 5.29001C1.07628 5.38297 1.00188 5.49357 0.951114 5.61543C0.900345 5.73729 0.874207 5.868 0.874207 6.00001C0.874207 6.13202 0.900345 6.26273 0.951114 6.38459C1.00188 6.50645 1.07628 6.61705 1.17 6.71001L5.41 11C5.50344 11.0927 5.61426 11.166 5.7361 11.2158C5.85794 11.2655 5.9884 11.2908 6.12 11.29C6.25161 11.2908 6.38207 11.2655 6.50391 11.2158C6.62575 11.166 6.73656 11.0927 6.83 11C7.01626 10.8126 7.1208 10.5592 7.1208 10.295C7.1208 10.0308 7.01626 9.77737 6.83 9.59001L3.29 6.00001Z" fill="#F97316"/>
                </svg>
                <span>Kembali</span>
            </button>
            <button class="bg-orange-500 hover:bg-orange-600 focus:ring-[4px] focus:ring-orange-400/20 text-white font-medium px-8 py-2 rounded-xl transition-colors">
                Selanjutnya
            </button>
        </div>
    </div>
</div>
@endsection

@push('script-body')
@endpush