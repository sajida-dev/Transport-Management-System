<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title') - LoadMasta Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    @livewireStyles

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head-scripts')

    <style>
        /* Custom scrollbar styles (optional) */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: #2d3748;
            /* gray-800 */
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #4a5568;
            /* gray-600 */
            border-radius: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #718096;
            /* gray-500 */
        }

        body.sidebar-open {
            overflow: hidden;
            height: 100vh;
        }
    </style>

</head>

<body class="h-full">
    <div class="min-h-screen bg-gray-100">
        <div x-data="{
            open: false,
            sidebarOpen: false,
            init() {
                this.$watch('sidebarOpen', value => {
                    document.body.classList.toggle('sidebar-open', value);
                });
            }
        }" @keydown.window.escape="sidebarOpen = false" x-cloak>
            <!-- Off-canvas menu for mobile -->
            <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-ref="dialog" aria-modal="true">
                <!-- Overlay -->
                <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-linear duration-300"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/80"></div>

                <!-- Sidebar panel -->
                <div class="fixed inset-0 flex">
                    <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in-out duration-300 transform"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                        class="relative mr-16 flex w-full max-w-xs flex-1">

                        <!-- Close button -->
                        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                            <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                <span class="sr-only">Close sidebar</span>
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar component for mobile -->
                        <!-- Sidebar component for mobile -->
                        <nav x-data="{ openCategory: null }"
                            class="flex flex-col bg-gray-900 text-white overflow-y-auto h-full">

                            <!-- Sticky header -->
                            <div class="sticky top-0 z-10 bg-gray-900 w-full px-5 py-4 border-b border-gray-800">
                                <div class="flex items-center gap-x-3">
                                    <div
                                        class="h-8 w-auto rounded-lg bg-indigo-600 flex items-center justify-center p-1">
                                        <i class="fas fa-truck-fast text-xl text-white"></i>
                                    </div>
                                    <span class="text-lg font-semibold text-white">LoadMasta</span>
                                </div>
                            </div>

                            <!-- Sidebar items -->
                            <ul role="list" class="flex flex-col gap-y-2 px-5 pb-4">
                                @foreach ($sidebarItems as $category => $section)
                                    @php
                                        $categoryIcon = $section[0];
                                        $menuItems = $section[1] ?? [];
                                    @endphp

                                    <li class="select-none">
                                        <button
                                            @click="openCategory === '{{ $category }}' ? openCategory = null : openCategory = '{{ $category }}'"
                                            class="flex items-center gap-3 w-full text-sm font-medium rounded-md px-2.5 py-1.5 transition duration-150 ease-in-out hover:text-white hover:bg-gray-800 text-gray-300 focus:outline-none">
                                            <i class="{{ $categoryIcon }} text-xs text-gray-300"></i>
                                            <span class="truncate">{{ $category }}</span>
                                            <svg :class="openCategory === '{{ $category }}' ? 'rotate-90 text-indigo-400' :
                                                'text-gray-400'"
                                                class="ml-auto w-4 h-4 transform transition-transform duration-200 ease-in-out"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>

                                        <ul x-show="openCategory === '{{ $category }}'" x-transition
                                            class="mt-1 space-y-1 rounded-md bg-gray-800 px-2 py-2 border-l-4 border-indigo-600"
                                            style="display: none;" role="list">

                                            @foreach ($menuItems as $item)
                                                @php
                                                    $canShow = true;

                                                    if (isset($item['role'])) {
                                                        $canShow = $user->hasRole($item['role']);
                                                    }

                                                    if (
                                                        $canShow &&
                                                        isset($item['permission']) &&
                                                        $item['permission'] !== null
                                                    ) {
                                                        $canShow = $user->can($item['permission']);
                                                    }

                                                    $routeName = $item['route'] ?? null;
                                                    $params = $item['params'] ?? [];

                                                    try {
                                                        $url =
                                                            $item['url'] ??
                                                            ($routeName ? route($routeName, $params) : '#');
                                                    } catch (Exception $e) {
                                                        $url = '#';
                                                    }

                                                    $isActive = $routeName && request()->routeIs($routeName . '*');
                                                @endphp

                                                @if ($canShow)
                                                    <li>
                                                        <a href="{{ $url }}"
                                                            @if (isset($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif
                                                            class="{{ $isActive ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:text-white hover:bg-indigo-600' }}
                                        flex items-center gap-3 rounded-md px-2.5 py-1.5 text-sm font-medium transition duration-150 ease-in-out"
                                                            @click="openCategory = null">
                                                            <div
                                                                class="flex items-center justify-center w-6 h-6 rounded bg-indigo-700 group-hover:bg-indigo-600 transition">
                                                                <i
                                                                    class="{{ $item['icon'] ?? 'fas fa-circle' }} text-xs text-white"></i>
                                                            </div>
                                                            <span class="truncate">{{ $item['label'] }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach

                                <!-- Sign out -->
                                <li class="mt-auto">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center gap-3 w-full px-2.5 py-1.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-red-600 rounded-md transition duration-150 ease-in-out focus:outline-none">
                                            <div
                                                class="flex items-center justify-center w-6 h-6 rounded bg-red-700 hover:bg-red-600 transition">
                                                <i class="fas fa-sign-out-alt text-xs text-white"></i>
                                            </div>
                                            <span class="truncate">Sign out</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </nav>


                    </div>
                </div>
            </div>
            <!-- Static sidebar for desktop -->
            <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-56 lg:flex-col">
                <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-2 pb-4 sidebar-scroll">
                    <div class="flex h-16 shrink-0 items-center">
                        <div class="flex items-center gap-x-3">
                            <div class="h-8 w-auto rounded-lg bg-indigo-600 flex items-center justify-center p-1">
                                <i class="fas fa-truck-fast text-xl text-white"></i>
                            </div>
                            <span class="text-lg font-semibold text-white">LoadMasta</span>
                        </div>
                    </div>
                    <nav x-data="{ sidebarOpen: false, openCategory: null }" class="flex flex-col h-full w-full bg-gray-900 text-gray-300">

                        <!-- Mobile header with hamburger -->
                        <div class="md:hidden flex items-center justify-between px-4 py-2 border-b border-gray-800">
                            <div class="text-lg font-semibold text-white">Menu</div>
                            <button @click="sidebarOpen = !sidebarOpen" aria-label="Toggle Sidebar"
                                class="text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Sidebar content -->
                        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                            class="fixed inset-y-0 left-0  bg-gray-900 overflow-y-auto transform md:transform-none md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-30">
                            <ul role="list" class="flex flex-col gap-y-2">

                                <!-- Loop through sidebar items -->
                                @foreach ($sidebarItems as $category => $section)
                                    @php

                                        $categoryIcon = $section[0];
                                        $menuItems = $section[1] ?? [];
                                    @endphp

                                    <li class="select-none">
                                        <button {{-- @click="open = !open" --}}
                                            @click="openCategory === '{{ $category }}' ? openCategory = null : openCategory = '{{ $category }}'"
                                            class="flex items-center gap-3 w-full text-sm font-medium rounded-md px-2.5 py-1.5 transition duration-150 ease-in-out hover:text-white hover:bg-gray-800 text-gray-300 focus:outline-none"
                                            :aria-expanded="open.toString()">
                                            <i class="{{ $categoryIcon }} text-xs text-gray-300"></i>
                                            <span class="truncate">{{ $category }}</span>
                                            <svg :class="openCategory === '{{ $category }}' ? 'rotate-90 text-indigo-400' :
                                                'text-gray-400'"
                                                class="ml-auto w-4 h-4 transform transition-transform duration-200 ease-in-out"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9
                                                5l7 7-7 7" />
                                            </svg>
                                        </button>

                                        <ul x-show="openCategory === '{{ $category }}'" x-transition
                                            class="mt-1 space-y-1 rounded-md bg-gray-800 px-2 py-2 border-l-4 border-indigo-600"
                                            style="display: none;" role="list">

                                            @foreach ($menuItems as $item)
                                                @php
                                                    $canShow = true;

                                                    if (isset($item['role'])) {
                                                        $canShow = $user->hasRole($item['role']);
                                                    }

                                                    if (
                                                        $canShow &&
                                                        isset($item['permission']) &&
                                                        $item['permission'] !== null
                                                    ) {
                                                        $canShow = $user->can($item['permission']);
                                                    }

                                                    $routeName = $item['route'] ?? null;
                                                    $params = $item['params'] ?? [];

                                                    try {
                                                        $url =
                                                            $item['url'] ??
                                                            ($routeName ? route($routeName, $params) : '#');
                                                    } catch (Exception $e) {
                                                        $url = '#';
                                                    }

                                                    $isActive = $routeName && request()->routeIs($routeName . '*');
                                                @endphp

                                                @if ($canShow)
                                                    <li>
                                                        <a href="{{ $url }}"
                                                            @if (isset($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif
                                                            class="{{ $isActive ? 'bg-indigo-700 text-white' : 'text-gray-300 hover:text-white hover:bg-indigo-600' }} 
                                flex items-center gap-3 rounded-md px-2.5 py-1.5 text-sm font-medium transition duration-150 ease-in-out">
                                                            <div
                                                                class="flex items-center justify-center w-6 h-6 rounded bg-indigo-700 group-hover:bg-indigo-600 transition">
                                                                <i
                                                                    class="{{ $item['icon'] ?? 'fas fa-circle' }} text-xs text-white"></i>
                                                            </div>
                                                            <span class="truncate">{{ $item['label'] }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach


                                <!-- Sign out at the bottom -->
                                <li class="mt-auto">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center gap-3 w-full px-2.5 py-1.5 text-sm font-medium text-gray-300 hover:text-white hover:bg-red-600 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500">
                                            <div
                                                class="flex items-center justify-center w-6 h-6 rounded bg-red-700 hover:bg-red-600 transition">
                                                <i class="fas fa-sign-out-alt text-xs text-white"></i>
                                            </div>
                                            <span class="truncate">Sign out</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        <!-- Overlay for mobile when sidebar open -->
                        <div x-show="sidebarOpen" @click="sidebarOpen = false"
                            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden" x-cloak></div>
                    </nav>
                    <!-- Alpine.js CDN if not already included -->
                    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
                </div>
            </div>

            <!-- Main content -->
            <div class="lg:pl-52">
                <!-- Top navigation -->
                <div
                    class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                    <!-- Mobile menu button -->
                    <button type="button"
                        class="lg:hidden -m-2.5 p-2.5 text-gray-700 hover:bg-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        @click="sidebarOpen = true" aria-label="Open sidebar">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Separator for mobile -->
                    <div class="h-6 w-px bg-gray-900/10 lg:hidden" aria-hidden="true"></div>

                    <div class="flex flex-1 gap-x-4 justify-between">

                        <div class="hidden sm:flex items-center gap-x-3">
                            @can('load.create')
                                <a href="{{ route('admin.loads.create') }}"
                                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-500 transition">
                                    <i class="fas fa-plus"></i>
                                    <span>Create Load</span>
                                </a>
                            @endcan
                            @can('driver.assign')
                                <a href="{{ route('admin.drivers.index') }}"
                                    class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-500 transition">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Assign Driver</span>
                                </a>
                            @endcan
                            @can('kyc.verify')
                                <a href="{{ route('admin.kyc.index') }}"
                                    class="inline-flex items-center gap-2 rounded-md bg-green-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-500 transition">
                                    <i class="fas fa-user-check"></i>
                                    <span>Approve KYC</span>
                                </a>
                            @endcan
                        </div>
                        <!-- Mobile Dropdown for Quick Actions -->
                        <div class="sm:hidden relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 focus:outline-none">
                                <i class="fas fa-bolt text-gray-700"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg ring-1 ring-gray-200 z-50">
                                <a href="{{ route('admin.loads.create') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-plus mr-2"></i> Create Load
                                </a>
                                <a href="{{ route('admin.drivers.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-plus mr-2"></i> Assign Driver
                                </a>
                                <a href="{{ route('admin.kyc.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-check mr-2"></i> Approve KYC
                                </a>
                            </div>
                        </div>

                        <!-- Right side: notifications and user profile -->
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <!-- Notifications dropdown -->
                            @include('components.notification-dropdown')

                            <!-- Vertical separator (desktop only) -->
                            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10" aria-hidden="true"></div>

                            <!-- Profile dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button type="button"
                                    class="flex items-center gap-x-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 p-1.5 hover:bg-gray-100 transition whitespace-nowrap"
                                    id="user-menu-button" @click="open = !open" aria-haspopup="true"
                                    :aria-expanded="open.toString()">
                                    <img class="h-8 w-8 rounded-full bg-gray-50 object-cover"
                                        src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                                        alt="{{ Auth::user()->name ?? 'User Avatar' }}" loading="lazy">
                                    <span
                                        class="hidden lg:flex items-center text-sm font-semibold text-gray-900 leading-5">
                                        {{ Auth::user()->name ?? 'Admin User' }}
                                        <svg class="ml-1 h-4 w-4 text-gray-400 flex-shrink-0" viewBox="0 0 20 20"
                                            fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </button>
                                <!-- Dropdown menu -->
                                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                    tabindex="-1" @click.away="open = false">
                                    <a href="{{ route('profile.show') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        role="menuitem" tabindex="-1">
                                        <i class="fas fa-user-circle mr-2"></i> Profile
                                    </a>
                                    <hr class="my-1 border-gray-200" />
                                    <form method="POST" action="{{ route('logout') }}" role="menuitem"
                                        tabindex="-1">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition rounded">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Sign out
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
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @once
        <script>
            function showToast(message, type = 'info') {
                const colors = {
                    success: "#16a34a", // Green
                    error: "#dc2626", // Red
                    warning: "#f59e0b", // Amber
                    info: "#3b82f6", // Blue
                };

                Toastify({
                    text: message,
                    duration: 4000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: colors[type] || colors.info,
                    close: true,
                    stopOnFocus: true
                }).showToast();
            }

            window.addEventListener('DOMContentLoaded', () => {
                @if (session('success'))
                    showToast(@json(session('success')), 'success');
                @endif

                @if (session('error'))
                    showToast(@json(session('error')), 'error');
                @endif

                @if (session('warning'))
                    showToast(@json(session('warning')), 'warning');
                @endif

                @if (session('info'))
                    showToast(@json(session('info')), 'info');
                @endif

                @if ($errors->any())
                    @foreach ($errors->all() as $message)
                        showToast(@json($message), 'error');
                    @endforeach
                @endif
            });
        </script>
    @endonce
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

        function showActivityLogs() {
            // For now, redirect to a simple activity logs page
            // You can enhance this with a modal or dedicated page
            window.location.href = '{{ route('admin.backoffice-users.index') }}?tab=activity-logs';
        }

        // Add any additional JavaScript functions here
        function showUserActivityLogs(userId) {
            fetch(`/admin/backoffice-users/${userId}/activity-logs`)
                .then(response => response.json())
                .then(data => {
                    // Handle the activity logs data
                    console.log('Activity logs:', data);
                    // You can show this in a modal or update a section
                })
                .catch(error => {
                    console.error('Error fetching activity logs:', error);
                });
        }

        function showUserSessionLogs(userId) {
            fetch(`/admin/backoffice-users/${userId}/session-logs`)
                .then(response => response.json())
                .then(data => {
                    // Handle the session logs data
                    console.log('Session logs:', data);
                    // You can show this in a modal or update a section
                })
                .catch(error => {
                    console.error('Error fetching session logs:', error);
                });
        }
    </script>
    @livewireScripts

</body>

</html>
