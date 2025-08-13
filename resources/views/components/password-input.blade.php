@props([
    'label' => '',
    'name',
    'value' => '',
    'required' => false,
    'autocomplete' => '',
])

<div x-data="{ show: false }" class="space-y-1">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input :type="show ? 'text' : 'password'" id="{{ $name }}" name="{{ $name }}"
            class="block w-full rounded-md border-gray-300 shadow-sm pr-10 focus:ring-indigo-500 focus:border-indigo-500"
            {{ $required ? 'required' : '' }} wire:model.defer="state.{{ $name }}"
            autocomplete="{{ $autocomplete }}" />

        <!-- Toggle icon -->
        <button type="button"
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm text-gray-500 focus:outline-none"
            x-on:click="show = !show" tabindex="-1">

            <svg id="eyeIcon" x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
            </svg>

            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.542-7a10.056 10.056 0 012.16-3.328m1.248-1.248A9.969 9.969 0 0112 5c4.478 0 8.269 2.943 9.542 7a10.05 10.05 0 01-4.034 5.302M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
            </svg>
        </button>
    </div>

    <x-input-error for="{{ $name }}" />
</div>
