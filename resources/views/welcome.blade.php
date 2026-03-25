<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Civic Guard</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&display=swap" rel="stylesheet">
        <!-- Styles / Scripts -->
        <!-- Tailwind CSS via CDN (Development Only) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
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
                        },
                        animation: {
                            blob: "blob 7s infinite",
                        },
                        keyframes: {
                            blob: {
                                "0%": {
                                    transform: "translate(0px, 0px) scale(1)",
                                },
                                "33%": {
                                    transform: "translate(30px, -50px) scale(1.1)",
                                },
                                "66%": {
                                    transform: "translate(-20px, 20px) scale(0.9)",
                                },
                                "100%": {
                                    transform: "translate(0px, 0px) scale(1)",
                                },
                            },
                        },
                    }
                }
            }
        </script>
    </head>
    <body class="antialiased font-sans bg-gray-50 text-gray-900">
        <div class="relative min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">
            <!-- Background Decoration -->
            <!-- Background Decoration - Restored to original -->
            <div class="fixed inset-0 -z-10 h-full w-full bg-white">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:14px_24px]"></div>
            </div>

            <!-- Top Bar / Navbar -->
            <div class="w-full bg-[#0a0b1e] relative z-20 border-b border-white/10 overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(to right, #ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
                
                <header class="relative w-full py-4 px-6 lg:px-8 flex justify-between items-center max-w-7xl mx-auto">
                    <div class="flex items-center gap-4">
                        <!-- Animated Round Govt Logo -->
                        <!-- Animated Round Govt Logo -->
                        <img src="{{ asset('images/logo1.png') }}" alt="Civic Guard Logo" class="w-24 h-24 drop-shadow-lg rounded-full bg-white p-0.5">

                        
                        <span class="text-2xl sm:text-3xl font-eagle-lake font-bold text-[#ffd9d9] tracking-wide capitalize drop-shadow-sm">
                            Public Asset Damage Reporting System
                        </span>
                    </div>
                </header>
            </div>

            <!-- Hero Section -->
            <div class="relative isolate overflow-hidden bg-slate-900" style="min-height: 85vh;">
                <!-- Hero Background Image -->
                <img src="{{ asset('images/hero.jpg') }}" alt="Government Building" class="absolute inset-0 h-full w-full object-cover" style="filter: brightness(0.8) contrast(1.05) saturate(1.1);">
                
                <!-- Blue Colored Shades/Overlays -->
                <div class="absolute inset-0 bg-blue-900/20 mix-blend-multiply"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-blue-950/60 via-blue-950/30 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-40"></div>

                <div class="mx-auto max-w-7xl px-6 lg:px-8 py-24 sm:py-32 relative z-10">
                    <div class="mx-auto max-w-2xl lg:mx-0">
                        <div class="mb-8 flex">
                            <div class="relative rounded-full px-3 py-1 text-sm leading-6 text-slate-300 ring-1 ring-white/10 hover:ring-white/20 bg-white/5 backdrop-blur-sm">
                                Empowering Citizens to <a href="#" class="font-semibold text-white">Take Action <span aria-hidden="true">&rarr;</span></a>
                            </div>
                        </div>
                        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">
                            <span class="block text-xl sm:text-2xl font-medium mb-2">Welcome to</span>
                            <span class="block text-6xl sm:text-8xl font-black">Civic Guard</span>
                        </h1>
                        <p class="mt-6 text-lg leading-8 text-slate-300 max-w-xl">
                            Quickly report damage to public assets and monitor the resolution process. Help ensure our city's infrastructure is safe and well-maintained.
                        </p>
                        <div class="mt-10 flex items-center gap-x-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="rounded-md bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300">
                                    Register
                                </a>
                                <a href="{{ route('login') }}" class="rounded-md bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-300">
                                    Login
                                </a>
                            @endauth
                        </div>
                    </div>


                </div>
            </div>

            <!-- What You Can Report Section -->
            <section class="py-24 relative z-10 bg-slate-50/50 overflow-hidden">
                <!-- Background Decoration - Professional Blue Shade Decor -->
                <div class="absolute inset-0 -z-10 pointer-events-none">
                    <!-- Multiple Blue / Indigo Glows -->
                    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-blue-100/40 rounded-full blur-[120px] -mr-48 -mt-24"></div>
                    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-indigo-100/30 rounded-full blur-[100px] -ml-48 -mb-24"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-50/20 rounded-full blur-[140px]"></div>
                    
                    <!-- Faint Grid Pattern -->
                    <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(#2563eb 0.5px, transparent 0.5px), linear-gradient(to right, #2563eb 0.5px, transparent 0.5px); background-size: 48px 48px;"></div>
                    
                    <!-- Floating Circles / Dots -->
                    <div class="absolute top-20 left-[10%] w-64 h-64 bg-blue-400/5 rounded-full blur-2xl"></div>
                    <div class="absolute bottom-40 right-[15%] w-48 h-48 bg-indigo-400/5 rounded-full blur-2xl"></div>

                    <!-- Dot Pattern Accents -->
                    <div class="absolute top-40 right-20 opacity-[0.1]" style="background-image: radial-gradient(#2563eb 1.5px, transparent 1.5px); background-size: 16px 16px; width: 120px; height: 120px;"></div>
                    <div class="absolute bottom-20 left-20 opacity-[0.1]" style="background-image: radial-gradient(#6366f1 1.5px, transparent 1.5px); background-size: 16px 16px; width: 100px; height: 100px;"></div>
                    
                    <!-- Small Geometric Accents -->
                    <div class="absolute top-1/3 left-20 w-3 h-3 bg-blue-300/30 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-1/3 right-20 w-4 h-4 bg-indigo-300/30 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                <div class="max-w-5xl mx-auto px-6 lg:px-8 relative">
                    <!-- Section Header -->
                    <div class="text-center mb-14">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide uppercase mb-4" style="background: rgba(37,99,235,0.1); color: #2563eb;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
                            Report Categories
                        </span>
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl" style="color: #0f172a;">What Can You Report?</h2>
                        <p class="mt-3 text-base max-w-2xl mx-auto" style="color: #64748b;">We handle a wide range of public infrastructure issues across multiple departments.</p>
                        <div class="mt-5 mx-auto w-16 h-1 rounded-full" style="background: linear-gradient(90deg, #2563eb, #3b82f6);"></div>
                    </div>

                    <!-- Top Row: 3 Main Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Roads & Footpaths -->
                        <div class="group relative flex flex-col items-center text-center gap-4 rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 border border-orange-100/80 bg-orange-50/30 hover:bg-orange-50 hover:shadow-[0_20px_40px_-15px_rgba(234,88,12,0.15)]">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-orange-400/20 to-transparent rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="h-16 w-16 rounded-full flex items-center justify-center shadow-sm transition-all duration-500 group-hover:bg-orange-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-orange-200" style="background: #ffffff; color: #ea580c; border: 1px solid #ffedd5;">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
                                </svg>
                            </div>
                            <div class="z-10">
                                <h3 class="text-xl font-bold mb-2 transition-colors duration-300 group-hover:text-orange-700" style="color: #0f172a;">Roads & Footpaths</h3>
                                <p class="text-sm leading-relaxed" style="color: #64748b;">Report potholes, broken sidewalks, and road surface damage with high GPS precision.</p>
                            </div>
                        </div>

                        <!-- Sanitation & Waste -->
                        <div class="group relative flex flex-col items-center text-center gap-4 rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 border border-emerald-100/80 bg-emerald-50/30 hover:bg-emerald-50 hover:shadow-[0_20px_40px_-15px_rgba(16,185,129,0.15)]">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-emerald-400/20 to-transparent rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="h-16 w-16 rounded-full flex items-center justify-center shadow-sm transition-all duration-500 group-hover:bg-emerald-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-emerald-200" style="background: #ffffff; color: #10b981; border: 1px solid #d1fae5;">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                </svg>
                            </div>
                            <div class="z-10">
                                <h3 class="text-xl font-bold mb-2 transition-colors duration-300 group-hover:text-emerald-700" style="color: #0f172a;">Sanitation & Waste</h3>
                                <p class="text-sm leading-relaxed" style="color: #64748b;">Manage illegal dumping, overflowing bins, and various public cleanliness concerns.</p>
                            </div>
                        </div>

                        <!-- Streetlights -->
                        <div class="group relative flex flex-col items-center text-center gap-4 rounded-2xl p-8 transition-all duration-500 hover:-translate-y-2 border border-indigo-100/80 bg-indigo-50/30 hover:bg-indigo-50 hover:shadow-[0_20px_40px_-15px_rgba(99,102,241,0.15)]">
                            <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-transparent via-indigo-400/20 to-transparent rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="h-16 w-16 rounded-full flex items-center justify-center shadow-sm transition-all duration-500 group-hover:bg-indigo-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-indigo-200" style="background: #ffffff; color: #6366f1; border: 1px solid #e0e7ff;">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/>
                                </svg>
                            </div>
                            <div class="z-10">
                                <h3 class="text-xl font-bold mb-2 transition-colors duration-300 group-hover:text-indigo-700" style="color: #0f172a;">Streetlights</h3>
                                <p class="text-sm leading-relaxed" style="color: #64748b;">Report non-functioning lights, damaged utility poles, or hazardous wiring issues.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Row: 6 Department Items -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Health & Sanitation -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-rose-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #fff1f2;">
                                <svg class="w-6 h-6" style="color: #e11d48;" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Health &<br>Sanitation</span>
                        </div>

                        <!-- Engineering & Public Works -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-blue-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #f0f7ff;">
                                <svg class="w-6 h-6" style="color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.384 3.084A1.125 1.125 0 014.5 17.18V6.82a1.125 1.125 0 011.536-1.074l5.384 3.084m0 6.34V8.83m0 6.34l5.384 3.084A1.125 1.125 0 0018 17.18V6.82a1.125 1.125 0 00-1.536-1.074L11.42 8.83"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Engineering &<br>Works</span>
                        </div>

                        <!-- Solid Waste Management -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-emerald-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #f0fdf4;">
                                <svg class="w-6 h-6" style="color: #10b981;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Solid Waste<br>Manage</span>
                        </div>

                        <!-- Electrical & Lighting -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-amber-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #fffbeb;">
                                <svg class="w-6 h-6" style="color: #d97706;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Electric &<br>Lighting</span>
                        </div>

                        <!-- Water Supply & Sewerage -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-blue-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #f0f7ff;">
                                <svg class="w-6 h-6" style="color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Water &<br>Sewerage</span>
                        </div>

                        <!-- Town Planning & Building -->
                        <div class="group flex flex-col items-center gap-4 rounded-xl px-4 py-6 border border-slate-200/60 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-purple-200/50">
                            <div class="shrink-0 h-12 w-12 rounded-full flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: #f5f3ff;">
                                <svg class="w-6 h-6" style="color: #7c3aed;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-center" style="color: #334155;">Town Planning<br>& Building</span>
                        </div>
                    </div>
                </div>
            </section>


            <!-- Shared Bottom Background Area (Security + Footer) -->
            <div class="relative z-10 overflow-hidden">
                <!-- Shared Background Image -->
                <div class="absolute inset-0 -z-10">
                    <img src="{{ asset('images/hero.jpg') }}" alt="" class="w-full h-full object-cover" style="filter: brightness(0.6) saturate(0.85);">
                    <!-- Seamless transition matching the section above -->
                    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(232, 238, 246, 1) 0%, rgba(232, 238, 246, 0.98) 12%, rgba(232, 238, 246, 0.8) 25%, rgba(15, 23, 42, 0.6) 60%, rgba(15, 23, 42, 0.95) 100%);"></div>
                </div>

                <!-- Security & Privacy Content (Already inside section tags but modified for transparency) -->
                <section class="pt-8 pb-12 relative overflow-hidden bg-transparent">
                    <!-- Decorative Background Elements -->
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div class="absolute -top-10 -left-16 w-60 h-60 rounded-full" style="background: radial-gradient(circle, rgba(37,99,235,0.05) 0%, transparent 70%);"></div>
                        <div class="absolute -bottom-10 -right-16 w-48 h-48 rounded-full" style="background: radial-gradient(circle, rgba(99,102,241,0.05) 0%, transparent 70%);"></div>
                        <svg class="absolute top-4 right-16 opacity-[0.06]" width="80" height="80" viewBox="0 0 80 80"><defs><pattern id="dots3" x="0" y="0" width="16" height="16" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.2" fill="#2563eb"/></pattern></defs><rect width="80" height="80" fill="url(#dots3)"/></svg>
                    </div>
                    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative">
                        <h2 class="text-2xl font-bold tracking-tight sm:text-3xl mb-4" style="color: #1e293b;">Your Security & Privacy</h2>
                        <p class="text-base leading-relaxed" style="color: #64748b;">
                            Your reports are securely stored and accessible only to authorized officials, ensuring your privacy is protected.
                        </p>
                    </div>
                </section>

                <!-- Footer Content -->
                <footer class="pt-12 pb-8 px-6 lg:px-8 relative">
                    <div class="max-w-5xl mx-auto text-center">
                        <div class="w-16 h-px bg-white/20 mx-auto mb-10"></div>
                        <!-- Footer Title -->
                        <div class="flex flex-col items-center gap-4 mb-3">
                            <img src="{{ asset('images/logo1.png') }}" alt="Civic Guard Logo" class="w-16 h-16 drop-shadow-lg rounded-full bg-white p-0.5 opacity-80 group-hover:opacity-100 transition-opacity">
                            <h3 class="text-2xl sm:text-3xl font-black text-white" style="text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                                CivicGuard – Public Asset Damage Reporting System
                            </h3>
                        </div>
                        <p class="text-xs text-white/50">&copy; {{ date('Y') }} CivicGuard. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
