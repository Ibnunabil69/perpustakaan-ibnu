<x-app-layout>
    <x-slot name="header">Tambah Anggota</x-slot>

    <div class="max-w-lg mx-auto">
        <x-ui.card>
            <!-- Decorative Header -->
            <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-5 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-user-add-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Anggota Baru</h3>
                    <p class="text-xs text-white/70">Daftarkan siswa sebagai anggota perpustakaan</p>
                </div>
            </div>

            <form action="{{ route('admin.anggota.store') }}" method="POST" class="p-5 space-y-5">
                @csrf

                <!-- Data Diri -->
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ri-user-line text-amber-400"></i> Data Diri
                    </p>
                    <div class="space-y-3">
                        <div>
                            <x-ui.label for="name">Nama Lengkap</x-ui.label>
                            <x-ui.input id="name" name="name" type="text" :value="old('name')" required autofocus placeholder="Nama lengkap siswa" icon="ri-user-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-ui.label for="email">Email</x-ui.label>
                            <x-ui.input id="email" name="email" type="email" :value="old('email')" required placeholder="nama@email.com" icon="ri-mail-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        </div>
                    </div>
                </div>

                <!-- Keamanan -->
                <div class="border-t border-amber-100 pt-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ri-lock-line text-teal-400"></i> Keamanan
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-ui.label for="password">Password</x-ui.label>
                            <x-ui.input id="password" name="password" type="password" required placeholder="Min. 8 karakter" icon="ri-lock-password-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('password')" />
                        </div>
                        <div>
                            <x-ui.label for="password_confirmation">Konfirmasi</x-ui.label>
                            <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Ulangi password" icon="ri-lock-password-line" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-amber-100">
                    <x-ui.button href="{{ route('admin.anggota.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit">
                        <i class="ri-user-add-line"></i> Tambah Anggota
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
