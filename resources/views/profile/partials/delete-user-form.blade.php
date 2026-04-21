<section class="space-y-6">
    <header>
        <h2 class="text-base font-semibold text-gray-800">
            {{ __('Hapus Akun') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    <x-ui.button
        variant="danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</x-ui.button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-semibold text-gray-800">
                {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.') }}
            </p>

            <div class="mt-5">
                <x-ui.label for="password" value="{{ __('Kata Sandi') }}" class="sr-only" />
                <x-ui.input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Kata Sandi') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
            </div>

            <div class="mt-5 flex justify-end gap-3">
                <x-ui.button variant="outline" x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-ui.button>

                <x-ui.button type="submit" variant="danger">
                    {{ __('Hapus Akun') }}
                </x-ui.button>
            </div>
        </form>
    </x-modal>
</section>
