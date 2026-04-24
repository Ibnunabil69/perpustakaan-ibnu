<x-guest-layout>
    <x-slot name="header">Daftar</x-slot>

    <!-- Header Section -->
    <div class="mb-10 text-center">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Bergabung Sekarang</h1>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
            Daftarkan akun Anda untuk mulai menggunakan layanan perpustakaan digital kami.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name Field -->
        <div class="space-y-1.5">
            <x-ui.label for="name" value="Nama Lengkap" class="text-sm font-semibold text-gray-700" />
            <x-ui.input 
                id="name" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name" 
                placeholder="Masukkan nama lengkap" 
                icon="ri-user-line"
                class="py-2.5"
            />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div class="space-y-1.5">
            <x-ui.label for="email" value="Email" class="text-sm font-semibold text-gray-700" />
            <x-ui.input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username" 
                placeholder="nama@email.com" 
                icon="ri-mail-line"
                class="py-2.5"
            />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <x-ui.label for="password" value="Password" class="text-sm font-semibold text-gray-700" />
                <x-ui.input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••" 
                    icon="ri-lock-2-line"
                    class="py-2.5"
                />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="space-y-1.5">
                <x-ui.label for="password_confirmation" value="Konfirmasi" class="text-sm font-semibold text-gray-700" />
                <x-ui.input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="••••••••" 
                    icon="ri-lock-check-line"
                    class="py-2.5"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full h-11 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-500/20 transition-all active:scale-[0.98] focus:outline-none">
                Daftar Sekarang
            </button>
        </div>

        <!-- Login Link -->
        <div class="pt-6 border-t border-gray-50 text-center">
            <p class="text-sm text-gray-500">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-amber-600 hover:text-amber-700 transition-colors">
                    Masuk Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
