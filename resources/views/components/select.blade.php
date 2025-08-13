@props([
    'label' => '',
    'name',
    'options' => [],
    'placeholder' => 'Select an option',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'helpText' => '',
])

<div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        @if ($multiple) multiple @endif @if ($required) required @endif
        @if ($disabled) disabled @endif
        class="block w-full mt-1 pl-3 pr-10 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500
            appearance-none bg-white
            {{ $multiple ? 'pr-3' : '' }}">
        @if (!$multiple && $placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}"
                @if ($multiple) {{ in_array($optionValue, old($name, (array) $value)) ? 'selected' : '' }}
                @else
                    {{ old($name, $value) == $optionValue ? 'selected' : '' }} @endif>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>


    @if ($helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif

    <x-input-error for="{{ $name }}" class="mt-1" />
</div>
