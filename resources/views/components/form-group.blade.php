@props([
    'label' => '',
    'for' => '',
    'required' => false,
    'error' => false,
    'helpText' => '',
    'inline' => false,
    'labelWidth' => 'sm:w-1/3',
    'fieldWidth' => 'sm:w-2/3'
])

<div {{ $attributes->merge(['class' => $inline ? 'sm:flex sm:items-start' : '']) }}>
    @if($label)
        <label for="{{ $for }}" class="{{ $inline ? $labelWidth . ' sm:pt-2' : '' }} block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="{{ $inline ? $fieldWidth : '' }}">
        {{ $slot }}

        @if($error)
            <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
        @endif

        @if($helpText)
            <p class="mt-1 text-sm text-gray-500">{{ $helpText }}</p>
        @endif
    </div>
</div>