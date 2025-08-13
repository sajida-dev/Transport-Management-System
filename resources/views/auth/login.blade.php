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
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 text-center md:text-left">Welcome to
                        LoadMasta</h2>
                    <p class="text-gray-500 mb-4 md:mb-6 text-center md:text-left">Please enter your email and password
                    </p>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3 md:mb-4">
                            <x-label for="email" value="Email" class="text-gray-700 mb-1 block" />
                            <x-input id="email" name="email" type="email"
                                class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
                                placeholder="name@company.com" :value="old('email')" required autofocus
                                autocomplete="username" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <x-label for="password" value="Password" class="text-gray-700 mb-1 block" />
                            <x-input id="password" name="password" type="password"
                                class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 pr-10"
                                placeholder="Password" required autocomplete="current-password" />
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-9 text-gray-600">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-4">
                            <label for="remember_me" class="flex items-center">
                                <x-checkbox id="remember_me" name="remember" />
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-xs text-purple-600 hover:underline">Forgot Password?</a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <x-button
                            class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                            Log in <span class="ml-2">→</span>
                        </x-button>

                        <div class="text-xs text-gray-500 mt-3 text-center">
                            By logging in, you agree to our <a href="#"
                                class="text-purple-600 hover:underline">Terms & Conditions</a>
                        </div>
                    </form>

                    <div class="mt-4 md:mt-6 text-center text-sm text-gray-600">
                        Don't have an account yet?
                        <a href="{{ route('register') }}" class="text-purple-700 font-semibold hover:underline">Create
                            Account</a>
                    </div>

                    <div class="flex justify-between text-xs text-gray-400 mt-8">
                        <span>Privacy</span>
                        <span>Terms of Service.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Toggle Script -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.542-7a10.056 10.056 0 012.16-3.328m1.248-1.248A9.969 9.969 0 0112 5c4.478 0 8.269 2.943 9.542 7a10.05 10.05 0 01-4.034 5.302M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                `;
            } else {
                passwordInput.type = "password";
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                `;
            }
        }
    </script>
</x-guest-layout>
