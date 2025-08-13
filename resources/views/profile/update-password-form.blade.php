<form method="POST" action="{{ route('user-password.update') }}" class="space-y-6 bg-white rounded-lg p-6 shadow-md">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-password-input name="current_password" label="Current Password" required autocomplete="current-password" />
        <x-password-input name="password" label="New Password" required autocomplete="new-password" />
        <x-password-input name="password_confirmation" label="Confirm Password" required autocomplete="new-password" />
    </div>

    <div class="flex items-center justify-end mt-6">
        @if (session('status') === 'password-updated')
            <p class="text-green-600 text-sm me-4">Saved.</p>
        @endif

        <x-button>
            {{ __('Save') }}
        </x-button>
    </div>
</form>
