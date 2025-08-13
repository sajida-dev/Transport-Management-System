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

            <!-- Right Side (Form) -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-12">
                <div class="w-full max-w-xs sm:max-w-md">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 text-center md:text-left">Forgot
                        Password</h2>
                    <p class="text-gray-500 mb-4 md:mb-6 text-center md:text-left">
                        No problem. Enter your email and we’ll send you a reset link.
                    </p>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" />

                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <x-label for="email" value="Email" class="text-gray-700 mb-1 block" />
                            <x-input id="email" name="email" type="email"
                                class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="name@company.com" :value="old('email')" required autofocus
                                autocomplete="username" />
                        </div>

                        <!-- Submit -->
                        <x-button
                            class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors">
                            Email Password Reset Link
                        </x-button>

                        <div class="text-xs text-gray-500 mt-3 text-center">
                            Remember your password? <a href="{{ route('login') }}"
                                class="text-purple-600 hover:underline">Login</a>
                        </div>
                    </form>

                    <div class="flex justify-between text-xs text-gray-400 mt-8">
                        <span>Privacy</span>
                        <span>Terms of Service.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
