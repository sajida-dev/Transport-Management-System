@props([
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'destructive' => false,
    'disabled' => false
])

@php
$baseClasses = 'block w-full text-left px-4 py-2 text-sm leading-5 focus:outline-none transition duration-150 ease-in-out';
$activeClasses = 'bg-gray-100 text-gray-900';
$inactiveClasses = 'text-gray-700 hover:bg-gray-50 hover:text-gray-900';
$destructiveClasses = 'text-red-600 hover:bg-red-50 hover:text-red-700';
$disabledClasses = 'opacity-50 cursor-not-allowed';

$classes = $baseClasses . ' ' . 
          ($destructive ? $destructiveClasses : ($disabled ? $disabledClasses : $inactiveClasses));
@endphp

@if($type === 'button')
    <button {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'disabled' : '' }}>
        @if($icon)
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </button>
@else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} {{ $disabled ? 'tabindex="-1"' : '' }}>
        @if($icon)
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
    </a>
@endif