<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($header) ? $header . ' | ' . config('app.name', 'PerpusKita') : config('app.name', 'PerpusKita') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-orange-50/40 text-gray-800">
    <div class="min-h-screen flex">
        <!-- Decorative Side Panel -->
        <div class="hidden lg:flex w-[420px] bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden flex-shrink-0">
            <!-- Decorative circles -->
            <div class="absolute -top-20 -left-20 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-orange-500/8 rounded-full blur-2xl"></div>

            <div class="flex flex-col justify-center items-center w-full px-10 relative z-10">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/20 mb-6">
                    <i class="ri-book-3-line text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2 tracking-tight">Perpus<span class="text-amber-400">Kita</span></h2>
                <p class="text-gray-400 text-sm text-center leading-relaxed">Sistem Perpustakaan Digital<br>untuk Sekolah Modern</p>

                <!-- Decorative dots -->
                <div class="flex gap-1.5 mt-8">
                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                    <div class="w-2 h-2 rounded-full bg-orange-400"></div>
                    <div class="w-2 h-2 rounded-full bg-teal-400"></div>
                </div>
            </div>
        </div>

        <!-- Form Area -->
        <div class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-sm">
                <!-- Mobile Logo -->
                <div class="text-center mb-8 lg:hidden">
                    <a href="/" class="inline-flex flex-col items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                            <i class="ri-book-3-line text-white text-2xl"></i>
                        </div>
                        <div>
                            <span class="font-semibold text-xl text-gray-800 tracking-tight">Perpus<span class="text-amber-600">Kita</span></span>
                            <p class="text-xs text-gray-400 mt-0.5">Sistem Perpustakaan Digital</p>
                        </div>
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl shadow-lg shadow-amber-100/30 p-6">
                    {{ $slot }}
                </div>

                <p class="text-center text-xs text-gray-400 mt-6">&copy; {{ date('Y') }} PerpusKita. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
