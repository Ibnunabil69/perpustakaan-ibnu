<section>
    <header>
        <h2 class="text-base font-semibold text-gray-800">
            {{ __('Perbarui Kata Sandi') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <div>
            <x-ui.label for="update_password_current_password" value="{{ __('Kata Sandi Saat Ini') }}" />
            <x-ui.input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        <div>
            <x-ui.label for="update_password_password" value="{{ __('Kata Sandi Baru') }}" />
            <x-ui.input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        <div>
            <x-ui.label for="update_password_password_confirmation" value="{{ __('Konfirmasi Kata Sandi') }}" />
            <x-ui.input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-amber-100">
            <x-ui.button type="submit">{{ __('Simpan') }}</x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-medium"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
