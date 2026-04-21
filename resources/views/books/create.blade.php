<x-app-layout>
    <x-slot name="header">Tambah Buku</x-slot>

    <div class="max-w-2xl  mx-auto">
        <x-ui.card>
            <!-- Decorative Header -->
            <div class="bg-gradient-to-r from-amber-400 to-orange-400 px-5 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-book-3-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Buku Baru</h3>
                    <p class="text-xs text-white/70">Lengkapi data buku di bawah ini</p>
                </div>
            </div>

            <form action="{{ route('admin.books.store') }}" method="POST" class="p-5 space-y-5">
                @csrf

                <!-- Info Utama -->
                <div>
                    <p
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ri-information-line text-amber-400"></i> Informasi Utama
                    </p>
                    <div class="space-y-3">
                        <div>
                            <x-ui.label>Judul Buku</x-ui.label>
                            <x-ui.input name="judul" :value="old('judul')" required placeholder="Contoh: Laskar Pelangi"
                                icon="ri-book-open-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('judul')" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <x-ui.label>Penulis</x-ui.label>
                                <x-ui.input name="penulis" :value="old('penulis')" required placeholder="Andrea Hirata"
                                    icon="ri-user-line" />
                                <x-input-error class="mt-1" :messages="$errors->get('penulis')" />
                            </div>
                            <div>
                                <x-ui.label>Penerbit</x-ui.label>
                                <x-ui.input name="penerbit" :value="old('penerbit')" required
                                    placeholder="Bentang Pustaka" icon="ri-building-2-line" />
                                <x-input-error class="mt-1" :messages="$errors->get('penerbit')" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Publikasi -->
                <div class="border-t border-amber-100 pt-5">
                    <p
                        class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <i class="ri-archive-line text-teal-400"></i> Detail Publikasi
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-ui.label>Tahun Terbit</x-ui.label>
                            <x-ui.input type="number" name="tahun_terbit" :value="old('tahun_terbit')" required
                                min="1900" max="{{ date('Y') + 1 }}" placeholder="2024" icon="ri-calendar-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('tahun_terbit')" />
                        </div>
                        <div>
                            <x-ui.label>Jumlah Stok</x-ui.label>
                            <x-ui.input type="number" name="stok" :value="old('stok')" required min="0" max="10000"
                                placeholder="10" icon="ri-stack-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('stok')" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-amber-100">
                    <x-ui.button href="{{ route('admin.books.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit">
                        <i class="ri-save-line"></i> Simpan Buku
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>