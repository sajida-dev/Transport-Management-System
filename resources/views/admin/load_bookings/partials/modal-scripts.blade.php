@push('scripts')
<script>
    // Generic modal handling functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Complete modal specific functions
    document.getElementById('completeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const notes = document.getElementById('completion_notes').value;
        if (!notes.trim()) {
            alert('Please provide completion notes before submitting.');
            return;
        }
        document.getElementById('completion_notes_hidden').value = notes;
        this.submit();
    });

    // Cancel modal specific functions
    document.getElementById('cancelForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const reason = document.getElementById('cancel_reason').value;
        if (!reason.trim()) {
            alert('Please provide a reason for cancellation before submitting.');
            return;
        }
        document.getElementById('cancel_reason_hidden').value = reason;
        this.submit();
    });

    // Close modals when clicking outside or pressing escape
    window.addEventListener('click', function(event) {
        const modals = ['completeModal', 'cancelModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && event.target === modal) {
                closeModal(modalId);
            }
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = ['completeModal', 'cancelModal'];
            modals.forEach(modalId => {
                closeModal(modalId);
            });
        }
    });
</script>
@endpush 