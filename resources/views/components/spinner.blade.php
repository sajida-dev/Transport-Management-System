@props([
    'size' => 'md',
    'color' => 'indigo',
    'type' => 'border'  // border or dots
])

@php
$sizes = [
    'xs' => [
        'border' => 'w-3 h-3 border',
        'dots' => 'w-1 h-1 mx-0.5',
    ],
    'sm' => [
        'border' => 'w-4 h-4 border-2',
        'dots' => 'w-1.5 h-1.5 mx-0.5',
    ],
    'md' => [
        'border' => 'w-6 h-6 border-2',
        'dots' => 'w-2 h-2 mx-1',
    ],
    'lg' => [
        'border' => 'w-8 h-8 border-3',
        'dots' => 'w-2.5 h-2.5 mx-1',
    ],
    'xl' => [
        'border' => 'w-10 h-10 border-4',
        'dots' => 'w-3 h-3 mx-1.5',
    ],
];

$colors = [
    'indigo' => [
        'border' => 'border-indigo-500',
        'dots' => 'bg-indigo-500',
    ],
    'blue' => [
        'border' => 'border-blue-500',
        'dots' => 'bg-blue-500',
    ],
    'green' => [
        'border' => 'border-green-500',
        'dots' => 'bg-green-500',
    ],
    'red' => [
        'border' => 'border-red-500',
        'dots' => 'bg-red-500',
    ],
    'yellow' => [
        'border' => 'border-yellow-500',
        'dots' => 'bg-yellow-500',
    ],
    'gray' => [
        'border' => 'border-gray-500',
        'dots' => 'bg-gray-500',
    ],
    'white' => [
        'border' => 'border-white',
        'dots' => 'bg-white',
    ],
];
@endphp

@if($type === 'border')
    <div {{ $attributes->merge([
        'class' => 'inline-block rounded-full animate-spin ' . 
                  $sizes[$size]['border'] . ' ' .
                  $colors[$color]['border'] . ' ' .
                  'border-t-transparent'
    ]) }}>
        <span class="sr-only">Loading...</span>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
        <div class="flex space-x-1">
            <div class="animate-bounce delay-75 rounded-full {{ $sizes[$size]['dots'] }} {{ $colors[$color]['dots'] }}"></div>
            <div class="animate-bounce delay-150 rounded-full {{ $sizes[$size]['dots'] }} {{ $colors[$color]['dots'] }}"></div>
            <div class="animate-bounce delay-300 rounded-full {{ $sizes[$size]['dots'] }} {{ $colors[$color]['dots'] }}"></div>
        </div>
        <span class="sr-only">Loading...</span>
    </div>
@endif