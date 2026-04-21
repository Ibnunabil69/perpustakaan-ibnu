<x-app-layout>
    <x-slot name="header">Edit Anggota</x-slot>

    <div class="max-w-lg">
        <x-ui.card>
            <!-- Decorative Header -->
            <div class="bg-gradient-to-r from-teal-500 to-emerald-500 px-5 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-user-settings-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Edit Anggota</h3>
                    <p class="text-xs text-white/70">{{ $user->name }}</p>
                </div>
            </div>

            <form action="{{ route('admin.anggota.update', $user->id) }}" method="POST" class="p-5 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ri-user-line text-amber-400"></i> Data Diri
                    </p>
                    <div class="space-y-3">
                        <div>
                            <x-ui.label for="name">Nama Lengkap</x-ui.label>
                            <x-ui.input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus icon="ri-user-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <x-ui.label for="email">Email</x-ui.label>
                            <x-ui.input id="email" name="email" type="email" :value="old('email', $user->email)" required icon="ri-mail-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-amber-100">
                    <x-ui.button href="{{ route('admin.anggota.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit">
                        <i class="ri-save-line"></i> Simpan
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>
