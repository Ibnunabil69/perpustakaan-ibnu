<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-lg font-semibold text-gray-800">Masuk ke akun Anda</h1>
        <p class="text-sm text-gray-500 mt-1">Silakan masuk untuk melanjutkan akses perpustakaan.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-ui.label value="Email" />
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <x-ui.label value="Password" class="mb-0" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-amber-600 hover:text-amber-700" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <x-ui.input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-amber-300 text-amber-500 focus:ring-amber-400" name="remember">
            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
        </div>

        <x-ui.button type="submit" class="w-full">Masuk</x-ui.button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500">
                Belum punya akun? <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-medium">Daftar</a>
            </p>
        @endif
    </form>
</x-guest-layout>
