<x-guest-layout>
    <x-slot name="header">Masuk</x-slot>

    <!-- Header Section: Standard Proportions -->
    <div class="mb-10 text-center">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h1>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
            Silakan masuk ke akun Anda untuk mengakses layanan perpustakaan digital kami.
        </p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Field -->
        <div class="space-y-2">
            <x-ui.label for="email" class="text-sm font-semibold text-gray-700">
                Email atau No. Telepon
            </x-ui.label>
            <x-ui.input 
                id="email" 
                type="text" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="Masukkan email Anda" 
                icon="ri-mail-line"
                class="py-2.5"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password Field -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <x-ui.label for="password" class="text-sm font-semibold text-gray-700">
                    Password
                </x-ui.label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-amber-600 hover:text-amber-700 transition-colors" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>
            <x-ui.input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="••••••••" 
                icon="ri-lock-2-line"
                class="py-2.5"
            />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0 transition-all cursor-pointer">
                <span class="ml-2.5 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <!-- Submit Button: Standard Height & Sizing -->
        <div class="pt-2">
            <button type="submit" class="w-full h-11 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all active:scale-[0.98] focus:outline-none">
                Masuk Sekarang
            </button>
        </div>

        <!-- Registration Link -->
        @if (Route::has('register'))
            <div class="pt-8 text-center">
                <p class="text-sm text-gray-500">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-amber-600 hover:text-amber-700 transition-colors">
                        Daftar Baru
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>
