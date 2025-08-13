@props([
    'label' => '',
    'name',
    'options' => [], // [id => 'Label']
    'selected' => [], // [id, id, id]
    'error' => false,
    'required' => false,
    'helpText' => '',
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="flex flex-wrap gap-2">
        @foreach($options as $optionValue => $optionLabel)
            <label class="relative inline-flex items-center cursor-pointer">
                <input
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $optionValue }}"
                    class="sr-only peer"
                    id="{{ $name . '_' . $optionValue }}"
                    {{ in_array($optionValue, old($name, $selected)) ? 'checked' : '' }}
                >
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

    @if($helpText)
        <p class="mt-2 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
</div>
