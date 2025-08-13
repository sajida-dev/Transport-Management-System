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

            <!-- Right Side -->
            <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-12">
                <div class="w-full max-w-xs sm:max-w-md">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 text-center md:text-left">Confirm Your
                        Password</h2>
                    <p class="text-gray-500 mb-4 md:mb-6 text-center md:text-left">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('password.confirm.store') }}">
                        @csrf

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <x-label for="password" value="Password" class="text-gray-700 mb-1 block" />
                            <x-input id="password" name="password" type="password"
                                class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 pr-10"
                                placeholder="Password" required autocomplete="current-password" />
                        </div>

                        <!-- Submit Button -->
                        <x-button
                            class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            Confirm
                        </x-button>
                    </form>

                    <div class="flex justify-between text-xs text-gray-400 mt-8">
                        <span>Privacy</span>
                        <span>Terms of Service</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
