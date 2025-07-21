@props([
    'type' => 'default',
    'size' => 'md',
    'rounded' => 'full',
    'dot' => false
])

@php
$types = [
    'default' => 'bg-gray-100 text-gray-800',
    'primary' => 'bg-indigo-100 text-indigo-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800'
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-0.5 text-sm',
    'lg' => 'px-3 py-0.5 text-base'
];

$roundedOptions = [
    'none' => 'rounded-none',
    'sm' => 'rounded-sm',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
    'full' => 'rounded-full'
];

$classes = $types[$type] . ' ' . 
          $sizes[$size] . ' ' . 
          $roundedOptions[$rounded] . ' ' . 
          'inline-flex items-center font-medium';
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="mr-1.5 h-2 w-2 rounded-full {{ str_replace('bg-', '', explode(' ', $types[$type])[0]) }}"></span>
    @endif
    {{ $slot }}
</span>