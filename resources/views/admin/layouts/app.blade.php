<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - LoadMasta Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom scrollbar styles (optional) */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #2d3748; /* gray-800 */
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4a5568; /* gray-600 */
            border-radius: 3px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #718096; /* gray-500 */
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen bg-gray-100">
        <div x-data="{ open: false }" @keydown.window.escape="open = false">
            <!-- Off-canvas menu for mobile -->
            <div x-show="open" class="relative z-50 lg:hidden" x-ref="dialog" aria-modal="true">
                <!-- Overlay -->
                <div x-show="open"
                    x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/80"></div>

                <!-- Sidebar panel -->
                <div class="fixed inset-0 flex">
                    <div x-show="open"
                        x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="relative mr-16 flex w-full max-w-xs flex-1">

                        <!-- Close button -->
                        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                            <button type="button" class="-m-2.5 p-2.5" @click="open = false">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar component for mobile -->
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4 ring-1 ring-white/10 sidebar-scroll">
                            <div class="flex h-16 shrink-0 items-center">
                                <div class="flex items-center gap-x-3">
                                     <div class="h-8 w-auto rounded-lg bg-indigo-600 flex items-center justify-center p-1">
                                         <i class="fas fa-truck-fast text-xl text-white"></i>
                                     </div>
                                    <span class="text-lg font-semibold text-white">LoadMasta</span>
                                </div>
                            </div>
                            <nav class="flex flex-1 flex-col">
                                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                    <li>
                                        <ul role="list" class="-mx-2 space-y-1">
                                            <li>
                                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                    <i class="fas fa-tachometer-alt h-6 w-6 shrink-0"></i>
                                                    Dashboard
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                    <i class="fas fa-users h-6 w-6 shrink-0"></i>
                                                    User Management
                                                </a>
                                            </li>
                                             <li>
                                                <a href="{{ route('admin.bookings', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.bookings*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                     <i class="fas fa-calendar-alt h-6 w-6 shrink-0"></i>
                                                    Bookings
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.trucks', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.trucks*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                    <i class="fas fa-truck h-6 w-6 shrink-0"></i>
                                                    Truck Management
                                                </a>
                                            </li>
                                             <li>
                                                 <a href="{{ route('admin.kyc', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.kyc*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                                     <i class="fas fa-file-contract h-6 w-6 shrink-0"></i>
                                                     KYC Management
                                                 </a>
                                             </li>
                                        </ul>
                                    </li>
                                    <li class="mt-auto">
                                        <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                            <i class="fas fa-user-cog h-6 w-6 shrink-0"></i>
                                            Profile Settings
                                        </a>
                                         <!-- Sign Out Form -->
                                         <form method="POST" action="{{ route('auth.logout') }}" class="mt-1">
                                             @csrf
                                             <button type="submit" class="group -mx-2 flex w-full gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:text-white hover:bg-gray-800">
                                                 <i class="fas fa-sign-out-alt h-6 w-6 shrink-0"></i>
                                                 Sign out
                                             </button>
                                         </form>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                 <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4 sidebar-scroll">
                     <div class="flex h-16 shrink-0 items-center">
                         <div class="flex items-center gap-x-3">
                             <div class="h-8 w-auto rounded-lg bg-indigo-600 flex items-center justify-center p-1">
                                 <i class="fas fa-truck-fast text-xl text-white"></i>
                             </div>
                             <span class="text-lg font-semibold text-white">LoadMasta</span>
                         </div>
                     </div>
                     <nav class="flex flex-1 flex-col">
                         <ul role="list" class="flex flex-1 flex-col gap-y-7">
                             <li>
                                 <div class="text-xs font-semibold leading-6 text-gray-400">Core Management</div>
                                 <ul role="list" class="-mx-2 mt-2 space-y-1">
                                     <li>
                                         <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-tachometer-alt h-6 w-6 shrink-0"></i>
                                             Dashboard
                                         </a>
                                     </li>
                                     <li>
                                         <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-users h-6 w-6 shrink-0"></i>
                                             User Management
                                         </a>
                                     </li>
                                     <li>
                                         <a href="{{ route('admin.bookings', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.bookings*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-calendar-alt h-6 w-6 shrink-0"></i>
                                             Bookings
                                         </a>
                                     </li>
                                      <li>
                                         <a href="{{ route('admin.trucks', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.trucks*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-truck h-6 w-6 shrink-0"></i>
                                             Truck Management
                                         </a>
                                     </li>
                                      <li>
                                         <a href="{{ route('admin.loads', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.loads*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-box h-6 w-6 shrink-0"></i>
                                             Load Management
                                         </a>
                                     </li>
                                     <li>
                                         <a href="{{ route('admin.kyc', ['selection' => 'all']) }}" class="{{ request()->routeIs('admin.kyc*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                             <i class="fas fa-file-contract h-6 w-6 shrink-0"></i>
                                             KYC Management
                                         </a>
                                     </li>
                                     <!-- Add more sections here -->
                                 </ul>
                             </li>
                             <!-- Optional: Add another section if needed -->
                             <!-- <li>
                                 <div class="text-xs font-semibold leading-6 text-gray-400">Analytics</div>
                                 <ul role="list" class="-mx-2 mt-2 space-y-1">
                                     <li> ... </li>
                                 </ul>
                             </li> -->
                             <li class="mt-auto">
                                 
                                  <!-- Sign Out Form -->
                                 <form method="POST" action="{{ route('auth.logout') }}" class="mt-1">
                                     @csrf
                                     <button type="submit" class="group -mx-2 flex w-full gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:text-white hover:bg-gray-800">
                                         <i class="fas fa-sign-out-alt h-6 w-6 shrink-0"></i>
                                         Sign out
                                     </button>
                                 </form>
                             </li>
                         </ul>
                     </nav>
                 </div>
             </div>


            <!-- Main content -->
            <div class="lg:pl-72">
                <!-- Top navigation -->
                 <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="open = true">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Separator -->
                    <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <form class="relative flex flex-1" action="#" method="GET">
                            <label for="search-field" class="sr-only">Search</label>
                            <svg class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            <input id="search-field" class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search features, users, trucks..." type="search" name="search">
                        </form>

                        <!-- Profile dropdown -->
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- Notifications -->
                            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                                <span class="sr-only">View notifications</span>
                                <i class="fas fa-bell h-6 w-6"></i>
                            </button>

                             <!-- Separator -->
                             <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>


                            <!-- Profile dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button type="button" class="-m-1.5 flex items-center p-1.5" id="user-menu-button" @click="open = !open">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Admin User Avatar"> <!-- Consider using dynamic avatar -->
                                    <span class="hidden lg:flex lg:items-center">
                                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">{{ Auth::user()->name ?? 'Admin User' }}</span> <!-- Use dynamic user name -->
                                        <svg class="ml-2 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>

                                <!-- Dropdown menu -->
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 z-10 mt-2.5 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                    role="menu"
                                    aria-orientation="vertical"
                                    aria-labelledby="user-menu-button"
                                    tabindex="-1"
                                    @click.away="open = false">
                                    <hr class="my-1 border-gray-200">
                                     <form method="POST" action="{{ route('auth.logout') }}" role="menuitem">
                                         @csrf
                                         <button type="submit" class="flex w-full items-center px-3 py-1 text-sm leading-6 text-red-600 hover:bg-red-50">
                                             <i class="fas fa-sign-out-alt mr-2"></i>
                                             Sign out
                                         </button>
                                     </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <main class="py-10">
                    <div class="px-4 sm:px-6 lg:px-8">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>