{{-- <x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-cloak x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('Code') }}" />
                    <x-input id="code" class="block mt-1 w-full" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('Recovery Code') }}" />
                    <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Use a recovery code') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Use an authentication code') }}
                    </button>

                    <x-button class="ms-4">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout> --}}

<x-guest-layout>
    <div class="min-h-screen bg-purple-800 flex items-center justify-center">
        <div
            class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl flex flex-col md:flex-row overflow-hidden m-2 md:m-0">

            <!-- Left Side -->
            <div class="w-full md:w-1/2 bg-purple-900 flex flex-col justify-between p-6 md:p-8 relative">
                <div>
                    <div class="flex items-center mb-4 md:mb-8">
                        <span class="text-white text-xl md:text-2xl font-bold tracking-wide">Load Masta</span>
                    </div>
                    <img src="{{ asset('truk.webp') ?? '/img/truk.webp' }}" alt="truk"
                        class="w-full h-40 md:h-72 object-contain mb-4 md:mb-8 mt-4 md:mt-8">
                </div>
                <div
                    class="flex flex-col md:flex-row justify-between text-xs text-purple-200 mt-4 md:mt-6 space-y-2 md:space-y-0">
                    <span>Load Masta — Transportation Management System</span>
                    <span>©2024 All Rights Reserved Switchd.</span>
                </div>
            </div>

            <!-- Right Side (Two-Factor Form) -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-12">
                <div class="w-full max-w-xs sm:max-w-md">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 text-center md:text-left">
                        Two-Factor Authentication
                    </h2>
                    <p class="text-gray-500 mb-6 text-center md:text-left">
                        Please confirm access to your account by entering the authentication code or recovery code.
                    </p>

                    <div x-data="{ recovery: false }">
                        <x-validation-errors class="mb-4" />

                        <form method="POST" action="{{ route('two-factor.login') }}">
                            @csrf

                            <div class="mb-4" x-show="!recovery">
                                <label for="code" class="block text-gray-700 mb-1 font-medium">Code</label>
                                <input id="code" name="code" type="text" inputmode="numeric" autofocus
                                    x-ref="code" autocomplete="one-time-code"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="mb-4" x-show="recovery" x-cloak>
                                <label for="recovery_code" class="block text-gray-700 mb-1 font-medium">Recovery
                                    Code</label>
                                <input id="recovery_code" name="recovery_code" type="text" x-ref="recovery_code"
                                    autocomplete="one-time-code"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400" />
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <button type="button"
                                    class="text-sm text-purple-600 hover:underline focus:outline-none"
                                    x-show="!recovery"
                                    x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                                    Use a recovery code
                                </button>

                                <button type="button"
                                    class="text-sm text-purple-600 hover:underline focus:outline-none" x-show="recovery"
                                    x-cloak x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                                    Use an authentication code
                                </button>
                            </div>

                            <button type="submit"
                                class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors">
                                Log in →
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
