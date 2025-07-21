@props([
    'type' => 'info',
    'dismissible' => false,
    'icon' => null
])

@php
$types = [
    'info' => [
        'bg' => 'bg-blue-50',
        'text' => 'text-blue-800',
        'border' => 'border-blue-300',
        'icon' => 'fa-info-circle text-blue-400',
        'close' => 'text-blue-500 hover:bg-blue-100'
    ],
    'success' => [
        'bg' => 'bg-green-50',
        'text' => 'text-green-800',
        'border' => 'border-green-300',
        'icon' => 'fa-check-circle text-green-400',
        'close' => 'text-green-500 hover:bg-green-100'
    ],
    'warning' => [
        'bg' => 'bg-yellow-50',
        'text' => 'text-yellow-800',
        'border' => 'border-yellow-300',
        'icon' => 'fa-exclamation-triangle text-yellow-400',
        'close' => 'text-yellow-500 hover:bg-yellow-100'
    ],
    'error' => [
        'bg' => 'bg-red-50',
        'text' => 'text-red-800',
        'border' => 'border-red-300',
        'icon' => 'fa-times-circle text-red-400',
        'close' => 'text-red-500 hover:bg-red-100'
    ]
];
@endphp

<div x-data="{ show: true }" x-show="show" {{ $attributes->merge(['class' => "{$types[$type]['bg']} {$types[$type]['text']} p-4 rounded-md border {$types[$type]['border']}"]) }}>
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas {{ $icon ?? $types[$type]['icon'] }} text-lg"></i>
        </div>
        <div class="ml-3">
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button type="button" @click="show = false" class="{{ $types[$type]['close'] }} rounded-md p-1.5 inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-{{ explode('-', $types[$type]['bg'])[1] }}-50 focus:ring-{{ explode('-', $types[$type]['bg'])[1] }}-600">
                        <span class="sr-only">Dismiss</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>