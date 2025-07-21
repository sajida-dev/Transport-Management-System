@props([
    'id',
    'title',
    'message',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'confirmButtonClass' => 'bg-red-600 hover:bg-red-700',
])

<x-modal :id="$id" maxWidth="sm">
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600">
            {{ $message }}
        </div>
    </div>

    <div class="px-6 py-4 bg-gray-100 text-right space-x-3">
        <button 
            type="button"
            x-on:click="show = false"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"
        >
            {{ $cancelText }}
        </button>

        {{ $slot }}
    </div>
</x-modal>