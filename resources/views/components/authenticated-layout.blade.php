<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-logo.png') }}">
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Scripts -->
    <!-- Tailwind CSS via CDN (Development Only) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                    fontFamily: {
                        sans: ['Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif', "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
                        'eagle-lake': ['"Eagle Lake"', 'serif'],
                    },
                    colors: {
                        gray: {
                            50: '#fafafa',
                            100: '#f4f4f5',
                            200: '#e4e4e7',
                            300: '#d4d4d8',
                            400: '#a1a1aa',
                            500: '#71717a',
                            600: '#52525b',
                            700: '#3f3f46',
                            800: '#27272a',
                            900: '#18181b',
                            950: '#09090b',
                        },
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/90 backdrop-blur-xl transition-all duration-300 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ Auth::user()->role === 'citizen' ? route('dashboard') : route('admin.dashboard') }}" class="flex items-center gap-3 group">
                                <div class="relative">
                                    <div class="absolute -inset-1 rounded-full bg-gradient-to-r from-red-600/30 to-orange-600/30 blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                    <img src="{{ asset('images/logo1.png') }}" alt="Logo" class="relative w-14 h-14 transition-transform duration-300 group-hover:scale-105 rounded-full">

                                </div>
                                <span class="text-xl font-eagle-lake font-bold text-[#8B0000] capitalize hidden sm:block tracking-wide drop-shadow-sm group-hover:text-red-700 transition-colors">
                                    {{ config('app.name') }}
                                </span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @if(Auth::user()->role === 'citizen')
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-[#8B0000] hover:border-[#8B0000] transition duration-300 ease-in-out">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-[#8B0000] hover:border-[#8B0000] transition duration-300 ease-in-out">
                                    {{ Auth::user()->isOfficer() ? 'Officer Dashboard' : 'Admin Dashboard' }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="flex items-center gap-4">
                            <!-- Notification Bell -->
                            @if(Auth::user()->role === 'citizen' || Auth::user()->role === 'officer')
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="relative group p-2 rounded-full text-gray-400 hover:text-[#8B0000] hover:bg-red-50 transition-all duration-300 outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <span class="sr-only">View notifications</span>
                                    <svg class="h-6 w-6 transition-transform group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-1 right-1 flex h-4 w-4">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 text-[10px] font-bold leading-none text-white bg-red-600 shadow-sm">
                                                {{ Auth::user()->unreadNotifications->count() }}
                                            </span>
                                        </span>
                                    @endif
                                </button>

                                <!-- Notification Dropdown -->
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     style="display: none;"
                                     class="absolute right-0 mt-2 w-80 origin-top-right rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 focus:outline-none z-[60] overflow-hidden">
                                    <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <h3 class="text-sm font-semibold text-slate-900">Notifications</h3>
                                        @if(Auth::user()->unreadNotifications->count() > 0)
                                            <form action="{{ route('notifications.readAll') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs text-[#8B0000] hover:text-red-700 font-medium hover:underline">Mark all read</button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                        @forelse(Auth::user()->notifications as $notification)
                                            <div class="relative px-4 py-3 hover:bg-slate-50 transition-colors {{ !$loop->last ? 'border-b border-slate-100' : '' }} {{ !$notification->read_at ? 'bg-red-50/30' : '' }}">
                                                <div class="flex justify-between items-start gap-3">
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $notification->data['title'] ?? 'System Notification' }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    @if(!$notification->read_at)
                                                        <div class="flex flex-col items-end gap-2">
                                                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="text-gray-400 hover:text-[#8B0000]" title="Mark as read">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-8 text-center text-gray-500">
                                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 mb-2">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                                </div>
                                                <p class="text-sm">No notifications yet</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    @if(Auth::user()->notifications->count() > 0)
                                    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50/50 text-center">
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-gray-500 hover:text-[#8B0000] font-bold transition-colors">View all notifications</a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="h-6 w-px bg-gray-200"></div>

                            <div class="flex items-center gap-3">
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-semibold text-gray-900">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ ucfirst(Auth::user()->role) }}
                                    </span>
                                </div>
                                
                                <div class="relative group">
                                    <button class="flex items-center justify-center h-10 w-10 rounded-full bg-[#8B0000] text-white font-bold text-lg shadow-md ring-2 ring-transparent hover:ring-red-300 transition-all duration-300 overflow-hidden">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div class="absolute right-0 mt-2 w-48 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 transform scale-95 group-hover:scale-100 z-50">
                                        <div class="py-1">
                                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#8B0000] transition-colors">
                                                Profile
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-red-600 transition-colors">
                                                    Log Out
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white border-b border-slate-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
@endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
