@props([
    'id' => null,
    'maxWidth' => '2xl',
    'title' => null,
    'closeButton' => true,
    'staticBackdrop' => false
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    'full' => 'sm:max-w-full',
][$maxWidth];
@endphp

<div x-data="{ show: false }"
    x-show="show"
    x-on:open-modal.window="$event.detail == '{{ $id }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $id }}' ? show = false : null"
    x-on:keydown.escape.window="if (!{{ $staticBackdrop ? 'true' : 'false' }}) show = false"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
    style="display: none;">
    
    <div x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="{{ $staticBackdrop ? '' : 'show = false' }}">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        @if($title || $closeButton)
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    @if($title)
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $title }}
                        </h3>
                    @endif
                    
                    @if($closeButton && !$staticBackdrop)
                        <button @click="show = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <div class="px-6 py-4">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>