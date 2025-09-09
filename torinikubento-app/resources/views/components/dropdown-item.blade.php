@props([
    'href' => null,
    'icon' => null,
    'color' => 'text-neutral-200',
    'hoverColor' => 'hover:text-white',
    'action' => null
])

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => "flex items-center px-4 py-2.5 text-sm {$color} hover:bg-neutral-700 {$hoverColor} transition-colors duration-200"]) }}>
        @if($icon)
            {!! $icon !!}
        @endif
        {{ $slot }}
    </a>
@else
    <button type="button" 
            @if($action) onclick="{{ $action }}" @endif
            {{ $attributes->merge(['class' => "w-full flex items-center px-4 py-2.5 text-sm {$color} hover:bg-neutral-700 {$hoverColor} transition-colors duration-200 text-left"]) }}>
        @if($icon)
            {!! $icon !!}
        @endif
        {{ $slot }}
    </button>
@endif
