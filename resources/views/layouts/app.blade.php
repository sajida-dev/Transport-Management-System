<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'LoadMasta') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="bg-white w-64 fixed h-screen shadow-lg">
            <div class="flex items-center justify-center h-16 border-b">
                <span class="text-2xl font-bold text-primary-600">LoadMasta</span>
            </div>
            <nav class="mt-6">
                <div class="px-4 py-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <ion-icon name="home-outline" class="w-5 h-5"></ion-icon>
                        <span class="mx-3">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.users.load_owners') }}" class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <ion-icon name="people-outline" class="w-5 h-5"></ion-icon>
                        <span class="mx-3">Users</span>
                    </a>
                    <a href="{{ route('admin.bookings', ['selection' => 'all']) }}" class="flex items-center px-4 py-3 mt-2 rounded-lg {{ request()->routeIs('admin.bookings') ? 'bg-primary-50 text-primary-600' : 'text-gray-600 hover:bg-gray-50' }}">
                        <ion-icon name="calendar-outline" class="w-5 h-5"></ion-icon>
                        <span class="mx-3">Bookings</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-8">
                    <div class="flex items-center">
                        <button class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <ion-icon name="menu-outline" class="w-6 h-6"></ion-icon>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="Admin">
                                <span>Admin</span>
                                <ion-icon name="chevron-down-outline" class="w-4 h-4"></ion-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-50 text-green-800 px-4 py-3 rounded-lg shadow-lg flex items-center" 
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            <ion-icon name="checkmark-circle" class="w-5 h-5 mr-2"></ion-icon>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="fixed bottom-4 right-4 bg-red-50 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-center"
             x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)">
            <ion-icon name="alert-circle" class="w-5 h-5 mr-2"></ion-icon>
            Please check the form and try again
        </div>
    @endif
</body>
</html>