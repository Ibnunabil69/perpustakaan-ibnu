<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-lg font-semibold text-gray-800">Buat akun baru</h1>
        <p class="text-sm text-gray-500 mt-1">Bergabunglah dengan PerpusKita untuk mulai meminjam buku.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-ui.label value="Nama Lengkap" />
            <x-ui.input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <x-ui.label value="Email" />
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-ui.label value="Password" />
                <x-ui.input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div>
                <x-ui.label value="Konfirmasi" />
                <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <x-ui.button type="submit" class="w-full">Daftar</x-ui.button>

        <p class="text-center text-sm text-gray-500">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-medium">Masuk</a>
        </p>
    </form>
</x-guest-layout>
