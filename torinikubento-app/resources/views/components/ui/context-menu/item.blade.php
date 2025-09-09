{{--
    Context Menu Item Component - Flexible without predefined icons/colors
    
    Usage:
    <x-ui.context-menu.item href="/view" class="text-neutral-200 hover:text-white">
        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        View Details
    </x-ui.context-menu.item>
    
    <x-ui.context-menu.item href="/edit" class="text-blue-400 hover:text-blue-300">
        <i class="fas fa-edit mr-3"></i>
        Edit
    </x-ui.context-menu.item>
--}}

@props([
    'href' => null,
    'onclick' => null,
    'method' => 'GET',
    'confirm' => null,
    'disabled' => false,
    'class' => ''
])

@php
$baseClasses = 'flex items-center px-4 py-2.5 text-sm rounded-lg hover:bg-neutral-700/60 active:bg-neutral-700';

if ($disabled) {
    $classes = $baseClasses . ' text-neutral-500 cursor-not-allowed';
} else {
    $classes = $baseClasses . ' ' . $class;
}
@endphp

@if($href && !$disabled)
    {{-- Link Item --}}
    <a href="{{ $href }}" 
       class="{{ $classes }}"
       @if($onclick) onclick="{{ $onclick }}" @endif>
        {{ $slot }}
    </a>
@elseif(!$disabled)
    {{-- Button Item (for forms or actions) --}}
    @if($method !== 'GET' && $href)
        <form method="POST" action="{{ $href }}" class="w-full">
            @csrf
            @if($method !== 'POST')
                @method($method)
            @endif
            <button type="submit" 
                    class="{{ $classes }} w-full text-left"
                    @if($onclick) onclick="{{ $onclick }}" @endif
                    @if($confirm) onclick="return confirm('{{ $confirm }}')" @endif>
                {{ $slot }}
            </button>
        </form>
    @else
        <button type="button" 
                class="{{ $classes }} w-full text-left"
                @if($onclick) onclick="{{ $onclick }}" @endif
                @if($confirm) onclick="return confirm('{{ $confirm }}')" @endif>
            {{ $slot }}
        </button>
    @endif
@else
    {{-- Disabled Item --}}
    <div class="{{ $classes }}">
        {{ $slot }}
    </div>
@endif
