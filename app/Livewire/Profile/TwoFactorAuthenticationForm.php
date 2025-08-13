<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Jetstream\ConfirmsPasswords;
use Laravel\Jetstream\HasRecoveryCodes;
use Laravel\Jetstream\Features;

class TwoFactorAuthenticationForm extends Component
{
    use ConfirmsPasswords, HasRecoveryCodes;

    public $enabled;
    public $showingQrCode = false;
    public $showingRecoveryCodes = false;
    public $showingConfirmation = false;
    public $code;

    public function mount()
    {
        $this->enabled = ! is_null(Auth::user()->two_factor_secret);
    }

    public function enableTwoFactorAuthentication()
    {
        if (! $this->enabled) {
            $this->confirmingPassword(function () {
                $this->showingQrCode = true;
                $this->enabled = true;
                Auth::user()->enableTwoFactorAuthentication();

                $this->showingConfirmation = Features::optionEnabled(
                    Features::twoFactorAuthentication(),
                    'confirm'
                ) ? true : false;

                $this->showingRecoveryCodes = true;
                $this->storeRecoveryCodes();
            });
        }
    }

    public function confirmTwoFactorAuthentication()
    {
        $provider = app(TwoFactorAuthenticationProvider::class);

        if (! $provider->verify(
            decrypt(Auth::user()->two_factor_secret),
            $this->code
        )) {
            throw ValidationException::withMessages([
                'code' => __('The provided two factor authentication code was invalid.'),
            ]);
        }

        Auth::user()->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes()
    {
        $this->confirmingPassword(function () {
            Auth::user()->recoveryCodes()->delete();
            $this->storeRecoveryCodes();
            $this->showingRecoveryCodes = true;
        });
    }

    public function showRecoveryCodes()
    {
        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactorAuthentication()
    {
        $this->confirmingPassword(function () {
            Auth::user()->disableTwoFactorAuthentication();
            $this->enabled = false;
            $this->showingQrCode = false;
            $this->showingRecoveryCodes = false;
        });
    }

    public function render()
    {
        return view('profile.two-factor-authentication-form');
    }
}
