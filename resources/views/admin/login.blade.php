<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LoadMasta</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!--@vite(['resources/css/app.css', 'resources/js/app.js'])-->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
</head>

<body class="min-h-screen bg-purple-800 flex items-center justify-center">
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl flex flex-col md:flex-row overflow-hidden m-2 md:m-0">
        <div class="w-full md:w-1/2 bg-purple-900 flex flex-col justify-between p-6 md:p-8 relative">
            <div>
                <div class="flex items-center mb-4 md:mb-8">
                    <span class="text-white text-xl md:text-2xl font-bold tracking-wide">Load Masta</span>
                </div>
                <img src="{{asset("truk.webp")}}" alt="Illustration" class="w-full h-40 md:h-72 object-contain mb-4 md:mb-8 mt-4 md:mt-8">
            </div>
            <div class="flex flex-col md:flex-row justify-between text-xs text-purple-200 mt-4 md:mt-6 space-y-2 md:space-y-0">
                <span>Load Masta — Transportation Management System</span>
                <span>©2024 All Rights Reserved Switchd.</span>
            </div>
        </div>
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center p-6 md:p-12">
            <div class="w-full max-w-xs sm:max-w-md">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 text-center md:text-left">Welcome to LoadMasta</h2>
                <p class="text-gray-500 mb-4 md:mb-6 text-center md:text-left">Please enter your email and password</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3 md:mb-4">
                        <label class="block text-gray-700 mb-1" for="email">Email</label>
                        <input id="email" name="email" value="{{ old('email') }}" type="email" placeholder="name@company.com" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 @error('email') border-red-300 text-red-900 placeholder-red-300 @enderror" required autofocus>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="block text-gray-700 mb-1" for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Password" class="w-full px-3 py-2 md:px-4 md:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400" required>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} id="remember" class="mr-2">
                            <label for="remember" class="text-xs text-gray-600">Remember me</label>
                        </div>
                        <a href="/forgot-password" class="text-xs text-purple-600 hover:underline">Forgot Password</a>
                    </div>
                    <button type="submit" class="w-full bg-purple-700 hover:bg-purple-800 text-white font-semibold py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        login... <span class="ml-2">→</span>
                    </button>
                    <div class="text-xs text-gray-500 mt-3 text-center">
                        By login, you agree to our <a href="#" class="text-purple-600 hover:underline">Terms & Conditions</a>
                    </div>
                </form>
                <div class="mt-4 md:mt-6 text-center text-sm text-gray-600">
                    Don't have an account yet? <a href="#" class="text-purple-700 font-semibold hover:underline">Create Account</a>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-8">
                    <span>Privacy</span>
                    <span>Terms of Service.</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>