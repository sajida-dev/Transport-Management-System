<!-- Cancel In-Transit Load Modal -->
<div id="cancelInTransitModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity hidden z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Cancel In-Transit Load</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to cancel this in-transit load? This action cannot be undone.</p>
                            <div class="mt-4">
                                <label for="cancel_reason" class="block text-sm font-medium text-gray-700">Cancellation Reason</label>
                                <textarea id="cancel_reason" name="cancel_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Please provide a reason for cancellation"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <form id="cancelInTransitForm" action="{{ route('admin.load_bookings.submit', ['selection' => $selection, 'booking_id' => $order->id]) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="cancel_in_transit">
                        <input type="hidden" id="cancel_reason_input" name="cancel_reason" value="">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Cancel Load</button>
                    </form>
                    <button type="button" onclick="closeCancelInTransitModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Go Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('cancelInTransitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const reason = document.getElementById('cancel_reason').value.trim();
        if (!reason) {
            alert('Please provide a cancellation reason');
            return;
        }
        document.getElementById('cancel_reason_input').value = reason;
        this.submit();
    });
</script>
@endpush 