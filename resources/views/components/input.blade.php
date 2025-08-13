@props([
    'type' => 'text',
    'label' => '',
    'name' => '',
    'title' => '',
    'value' => '',
    'placeholder' => '',
    'disabled' => false,
    'required' => false,
    'autocomplete' => 'off',
    'error' => false,
    'helpText' => '',
    'icon' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if ($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
        @endif

        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" title="{{ $title }}"
            value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" autocomplete="{{ $autocomplete }}"
            {{ $disabled ? 'disabled' : '' }} {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' =>
                    'block w-full rounded-md ' .
                    ($icon ? 'pl-10' : 'pl-3') .
                    ' pr-3 py-2 ' .
                    ($error
                        ? 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500') .
                    ($disabled ? ' bg-gray-100 cursor-not-allowed' : ''),
            ]) }} />

        @if ($error)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
        @endif
    </div>

    @if ($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif

    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
