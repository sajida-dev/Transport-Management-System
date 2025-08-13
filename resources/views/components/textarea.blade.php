@props([
    'label' => '',
    'name',
    'value' => '',
    'placeholder' => '',
    'rows' => 4,
    'disabled' => false,
    'required' => false,
    'error' => $errors->has($name),
])

<div class="w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        class="w-full px-3 py-2 border {{ $error ? 'border-red-500' : 'border-gray-300' }}
            rounded-md focus:ring-indigo-500 focus:border-indigo-500
            shadow-sm placeholder-gray-400 text-sm
            {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
