@props([
    'label' => '',
    'name',
    'options' => [], // array of value => label pairs
    'selected' => [], // array of selected values
    'error' => $errors->has($name),
])

<div class="w-full">
    @if($label)
        <p class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</p>
    @endif

    <div class="flex flex-wrap gap-2">
        @foreach($options as $optionValue => $optionLabel)
            @php
                $isChecked = in_array($optionValue, old($name, $selected));
                $inputId = $name . '_' . $optionValue;
            @endphp
            <label for="{{ $inputId }}" class="cursor-pointer">
                <input
                    type="checkbox"
                    name="{{ $name }}[]"
                    id="{{ $inputId }}"
                    value="{{ $optionValue }}"
                    class="hidden peer"
                    {{ $isChecked ? 'checked' : '' }}
                />
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border
                    text-gray-700 bg-gray-100 border-gray-300
                    peer-checked:bg-indigo-100 peer-checked:text-indigo-700 peer-checked:border-indigo-500
                    hover:bg-indigo-50">
                    {{ $optionLabel }}
                </span>
            </label>
        @endforeach
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

