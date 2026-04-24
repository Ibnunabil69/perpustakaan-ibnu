<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($header) ? $header . ' | ' . config('app.name', 'PerpusKita') : config('app.name', 'PerpusKita') }}</title>

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons: Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-orange-50/40 bg-warm-pattern text-gray-800 min-h-screen relative overflow-x-hidden">

    <div class="min-h-screen block overflow-x-hidden" 
         x-data="{ 
            notifications: [
                @if(session('success')) { id: Date.now(), message: '{!! addslashes(session('success')) !!}', type: 'success' }, @endif
                @if(session('error')) { id: Date.now() + 1, message: '{!! addslashes(session('error')) !!}', type: 'error' }, @endif
                @if($errors->any())
                    { id: Date.now() + 2, message: 'Ups! Mohon periksa kembali pengisian form Anda.', type: 'error' },
                @endif
            ] 
         }" 
         x-init="notifications.forEach(n => setTimeout(() => notifications = notifications.filter(x => x.id !== n.id), 4000))"
         @notify.window="
            const id = Date.now();
            notifications.push({ id, ...$event.detail });
            setTimeout(() => notifications = notifications.filter(n => n.id !== id), 4000);
         ">
        @include('layouts.navigation')

        <div class="flex-1 lg:ml-56 flex flex-col min-h-screen transition-all duration-300 pt-14 text-sm">

            @isset($header)
                <header class="hidden lg:block fixed top-0 right-0 lg:left-56 z-30 bg-white/70 backdrop-blur-md border-b border-amber-100/50">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-4">
                        <!-- Minimalist Page Title / Breadcrumb -->
                        <div class="flex items-center gap-2 overflow-hidden flex">
                           <div class="md:flex hidden items-center gap-2">
                                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Portal</span>
                                <i class="ri-arrow-right-s-line text-gray-300"></i>
                           </div>
                            <h1 class="text-[11px] sm:text-xs font-bold text-gray-600 tracking-tight truncate">{{ $header }}</h1>
                        </div>
                        <div class="md:hidden block"></div> 

                    </div>
                </header>
            @endisset

            <main class="flex-1 py-5 sm:py-8 animate-fade-in-up">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Toast Notifications -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-3 w-80 sm:w-96">
            <template x-for="n in notifications" :key="n.id">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 translate-x-8"
                     class="relative overflow-hidden bg-white rounded-xl shadow-xl shadow-gray-200/50 border border-gray-100">
                    <!-- Colored top accent -->
                    <div :class="n.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'" class="h-1 w-full">
                        <div class="h-full bg-white/30 animate-[shrink_4s_linear_forwards]"></div>
                    </div>
                    <div class="p-4 flex items-start gap-3">
                        <!-- Icon -->
                        <div :class="n.type === 'success' ? 'bg-emerald-50 text-emerald-600 ring-emerald-100' : 'bg-rose-50 text-rose-600 ring-rose-100'" class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 ring-1">
                            <i :class="n.type === 'success' ? 'ri-check-double-line' : 'ri-error-warning-line'" class="text-sm"></i>
                        </div>
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <p :class="n.type === 'success' ? 'text-emerald-800' : 'text-rose-800'" class="text-xs font-black uppercase tracking-widest mb-0.5" x-text="n.type === 'success' ? 'Berhasil' : 'Gagal'"></p>
                            <p class="text-sm text-gray-600 leading-relaxed" x-text="n.message"></p>
                        </div>
                        <!-- Close -->
                        <button @click="notifications = notifications.filter(x => x.id !== n.id)" class="text-gray-300 hover:text-gray-500 transition-colors flex-shrink-0 mt-0.5">
                            <i class="ri-close-line text-lg"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
    @stack('modals')
    @stack('scripts')

</body>
</html>