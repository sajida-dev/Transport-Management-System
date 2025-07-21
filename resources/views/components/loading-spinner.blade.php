<div
    x-data="{ show: false }"
    x-show="show"
    x-on:loading.window="show = true"
    x-on:loading-complete.window="show = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    style="display: none;"
>
    <div class="flex flex-col items-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        <p class="mt-4 text-white">Loading...</p>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Show loading spinner during form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                window.dispatchEvent(new CustomEvent('loading'));
            });
        });

        // Show loading spinner during page transitions
        document.addEventListener('turbolinks:request-start', () => {
            window.dispatchEvent(new CustomEvent('loading'));
        });

        document.addEventListener('turbolinks:request-end', () => {
            window.dispatchEvent(new CustomEvent('loading-complete'));
        });

        // Handle AJAX requests
        document.addEventListener('ajax:send', () => {
            window.dispatchEvent(new CustomEvent('loading'));
        });

        document.addEventListener('ajax:complete', () => {
            window.dispatchEvent(new CustomEvent('loading-complete'));
        });
    });
</script>
@endpush