<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PerpusKita') }}</title>

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons: Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-orange-50/40 bg-warm-pattern text-gray-800 min-h-screen relative">

    <div class="min-h-screen flex" x-data="{ notifications: [] }" @notify.window="
        const id = Date.now();
        notifications.push({ id, ...$event.detail });
        setTimeout(() => notifications = notifications.filter(n => n.id !== id), 4000);
    ">
        @include('layouts.navigation')

        <div class="flex-1 lg:ml-56 flex flex-col min-h-screen">

            @isset($header)
                <header class="bg-white/80 backdrop-blur-sm border-b border-amber-100">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-4">
                        <h1 class="text-base font-semibold text-gray-800">{{ $header }}</h1>

                        @if(Auth::user()->role !== 'admin')
                            <form action="{{ route('siswa.books') }}" method="GET" class="w-full max-w-xs">
                                <div class="relative">
                                    <i class="ri-search-2-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku..."
                                        class="w-full pl-9 pr-3 py-1.5 text-sm border border-amber-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-amber-400/40 focus:border-amber-300 transition-all">
                                </div>
                            </form>
                        @endif
                    </div>
                </header>
            @endisset

            <main class="flex-1 py-6 animate-fade-in-up">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div x-init="$dispatch('notify', { message: '{{ session('success') }}', type: 'success' })"></div>
                    @endif
                    @if(session('error'))
                        <div x-init="$dispatch('notify', { message: '{{ session('error') }}', type: 'error' })"></div>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Toast -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-2 w-72">
            <template x-for="n in notifications" :key="n.id">
                <div x-transition class="p-3 bg-white rounded-xl shadow-lg shadow-amber-100/50 border border-amber-100 flex items-start gap-2.5 text-sm">
                    <div :class="n.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'" class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i :class="n.type === 'success' ? 'ri-check-line' : 'ri-error-warning-line'" class="text-xs"></i>
                    </div>
                    <p class="flex-1 text-gray-700" x-text="n.message"></p>
                    <button @click="notifications = notifications.filter(x => x.id !== n.id)" class="text-gray-300 hover:text-gray-500 transition-colors">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </template>
        </div>
    </div>

</body>
</html>