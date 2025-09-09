@extends('partials.layouts.auth')

@section('main-content')
<div class="h-screen flex flex-col">
    <div class="flex flex-col h-full w-full items-center justify-center">
    	{{-- Header --}}
    	<div class="text-center mb-8">
    	    <h2 class="text-5xl font-bold mb-6">Selamat Datang</h2>
    	    <p class="text-neutral-500 text-xl font-medium mb-6">Pilih role untuk masuk</p>
    	</div>

    	{{-- Role Cards --}}
    	<div class="grid grid-cols-2 gap-4 w-full max-w-xl mx-auto">
    	    {{-- Owner Card --}}
    	    <div class="border border-neutral-600/50 rounded-3xl p-4 hover:bg-neutral-800/70 active:bg-neutral-800 cursor-pointer" onclick="window.location.href='{{ route('login.owner') }}'">
    	        <div class="flex items-center space-x-4">
    	            <div class="w-12 h-12 bg-neutral-700/50 rounded-xl flex items-center justify-center">
    	                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
    	                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
    	                </svg>
    	            </div>
    	            <div class="flex-1">
    	                <p class="text-sm text-white mb-1">Saya Seorang</p>
    	                <h3 class="text-neutral-400">Owner Restoran</h3>
    	            </div>
    	        </div>
    	    </div>

    	    {{-- Manager Card --}}
    	    <div class="border border-neutral-600/50 rounded-3xl p-4 hover:bg-neutral-800/70 active:bg-neutral-800 cursor-pointer" onclick="window.location.href='{{ route('login.manager') }}'">
    	        <div class="flex items-center space-x-4">
    	            <div class="w-12 h-12 bg-neutral-700/50 rounded-xl flex items-center justify-center">
    	                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
    	                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
    	                </svg>
    	            </div>
    	            <div class="flex-1">
    	                <p class="text-sm text-white mb-1">Saya Seorang</p>
    	                <h3 class="text-neutral-400">Manager Restoran</h3>
    	            </div>
    	        </div>
    	    </div>

    	    {{-- Chef Card --}}
    	    <div class="border border-neutral-600/50 rounded-3xl p-4 hover:bg-neutral-800/70 active:bg-neutral-800 cursor-pointer" onclick="window.location.href='{{ route('login.chef') }}'">
    	        <div class="flex items-center space-x-4">
    	            <div class="w-12 h-12 bg-neutral-700/50 rounded-xl flex items-center justify-center">
    	                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
    	                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
    	                </svg>
    	            </div>
    	            <div class="flex-1">
    	                <p class="text-sm text-white mb-1">Saya Seorang</p>
    	                <h3 class="text-neutral-400">Chef</h3>
    	            </div>
    	        </div>
    	    </div>

    	    {{-- Waiter Card --}}
    	    <div class="border border-neutral-600/50 rounded-3xl p-4 hover:bg-neutral-800/70 active:bg-neutral-800 cursor-pointer" onclick="window.location.href='{{ route('login.waiter') }}'">
    	        <div class="flex items-center space-x-4">
    	            <div class="w-12 h-12 bg-neutral-700/50 rounded-xl flex items-center justify-center">
    	                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
    	                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
    	                </svg>
    	            </div>
    	            <div class="flex-1">
    	                <p class="text-sm text-white mb-1">Saya Seorang</p>
    	                <h3 class="text-neutral-400">Pelayan</h3>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </div>
</div>
@endsection

@push('script-body')
@endpush