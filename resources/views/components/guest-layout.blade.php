<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-logo.png') }}">

    <!-- Scripts -->
    <!-- Tailwind CSS via CDN (Development Only) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
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
        }
    </script>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 relative">
        <!-- Full Screen Background -->
        <div class="fixed inset-0 z-0">
            <div class="absolute inset-0 bg-[url('/images/hero.jpg')] bg-cover bg-center"></div>
            <div class="absolute inset-0 bg-slate-50/40 backdrop-blur-sm"></div>
        </div>

        <div class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border border-slate-200">
            <div class="flex flex-col items-center mb-6">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <img src="{{ asset('images/logo1.png') }}" alt="Civic Guard Logo" class="w-32 h-32 drop-shadow-xl transition-transform duration-300 group-hover:scale-110 rounded-full">
                    <span class="text-3xl font-eagle-lake font-bold text-[#8B0000] tracking-wide capitalize drop-shadow-sm text-center">
                        Civic Guard
                    </span>
                </a>
            </div>

            <div class="w-full">
                {{ $slot }}
            </div>
        </div>

        @if (isset($footer))
            <div class="relative z-10 w-full sm:max-w-md mt-4 flex justify-center">
                {{ $footer }}
            </div>
        @endif
    </div>
</body>
</html>
