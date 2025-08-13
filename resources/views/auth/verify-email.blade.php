{{-- <x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('Resend Verification Email') }}
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Edit Profile') }}</a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
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

            <!-- Right Side (Email Verification) -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-12">
                <div class="w-full max-w-xs sm:max-w-md">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4 text-center md:text-left">
                        Verify Your Email Address
                    </h2>

                    <p class="text-gray-500 mb-6 text-center md:text-left">
                        Before continuing, could you verify your email address by clicking on the link we just emailed
                        to you? If you didn’t receive the email, we will gladly send you another.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600 text-center md:text-left">
                            A new verification link has been sent to the email address you provided in your profile
                            settings.
                        </div>
                    @endif

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 md:gap-0">
                        <form method="POST" action="{{ route('verification.send') }}" class="w-full md:w-auto">
                            @csrf
                            <button type="submit"
                                class="w-full md:w-auto bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors">
                                Resend Verification Email
                            </button>
                        </form>

                        <div class="text-center md:text-right space-x-2">
                            <a href="{{ route('profile.show') }}"
                                class="text-purple-700 font-semibold hover:underline text-sm">
                                Edit Profile
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-purple-700 font-semibold hover:underline text-sm ms-2">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
