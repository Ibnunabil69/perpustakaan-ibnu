<x-app-layout>
    <x-slot name="header">Tambah Buku</x-slot>

    <div class="max-w-4xl mx-auto">
        <x-ui.card>
            <!-- Decorative Header -->
            <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-t-2xl px-5 py-4 flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="ri-book-3-line text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-white">Buku Baru</h3>
                    <p class="text-xs text-white/70">Lengkapi data buku di bawah ini</p>
                </div>
            </div>

            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6" x-data="{ imageUrl: null }">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                    <!-- Left: Cover Preview (Smaller) -->
                    <div class="md:col-span-3 flex flex-col items-center gap-3">
                        <x-ui.label for="cover" class="w-full text-center">Sampul Buku</x-ui.label>

                        <!-- Preview Box -->
                        <div
                            class="relative group aspect-[3/4] w-full max-w-[160px] rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex flex-col items-center justify-center overflow-hidden transition-all hover:border-amber-300 shadow-sm bg-white">
                            <template x-if="imageUrl">
                                <img :src="imageUrl" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imageUrl">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="ri-image-add-line text-4xl mb-2"></i>
                                    <span class="text-[10px] font-bold tracking-wider">Preview Sampul</span>
                                </div>
                            </template>

                            <!-- Custom File Input Overlay -->
                            <input type="file" name="cover" id="cover"
                                @change="imageUrl = URL.createObjectURL($event.target.files[0])"
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*">
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 italic">Format: JPG, PNG (Maks 2MB)</p>
                        </div>
                        <x-input-error class="mt-1 w-full text-center" :messages="$errors->get('cover')" />
                    </div>

                    <!-- Right: Main Info -->
                    <div class="md:col-span-9 flex flex-col gap-5">
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Title -->
                            <div>
                                <x-ui.label for="judul">Judul Buku</x-ui.label>
                                <x-ui.input id="judul" name="judul" :value="old('judul')" required
                                    placeholder="Isi Judul Buku..." icon="ri-book-open-line" />
                                <x-input-error class="mt-1" :messages="$errors->get('judul')" />
                            </div>

                            <!-- Category Select -->
                            <div>
                                <x-ui.label for="category_id">Kategori</x-ui.label>
                                <x-ui.select id="category_id" name="category_id" :value="old('category_id')" placeholder="Pilih Kategori">
                                    <x-ui.select-option value="">Pilih Kategori</x-ui.select-option>
                                    @foreach ($categories as $category)
                                        <x-ui.select-option value="{{ $category->id }}">{{ $category->name }}</x-ui.select-option>
                                    @endforeach
                                </x-ui.select>
                                <x-input-error class="mt-1" :messages="$errors->get('category_id')" />
                            </div>

                            <!-- Author & Publisher -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-ui.label for="penulis">Penulis</x-ui.label>
                                    <x-ui.input id="penulis" name="penulis" :value="old('penulis')" required
                                        placeholder="Tulis Nama Penulis..." icon="ri-user-line" />
                                    <x-input-error class="mt-1" :messages="$errors->get('penulis')" />
                                </div>
                                <div>
                                    <x-ui.label for="penerbit">Penerbit</x-ui.label>
                                    <x-ui.input id="penerbit" name="penerbit" :value="old('penerbit')" required
                                        placeholder="Tulis Nama Penerbit..." icon="ri-building-line" />
                                    <x-input-error class="mt-1" :messages="$errors->get('penerbit')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description & Numbers Row -->
                <div class="space-y-6 pt-6 border-t border-gray-100">
                    <div>
                        <x-ui.label for="deskripsi">Deskripsi / Sinopsis</x-ui.label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                            class="w-full rounded-2xl border-gray-200 focus:border-amber-500 focus:ring-amber-500 text-sm p-4 transition-all"
                            placeholder="Tuliskan ringkasan singkat buku ini...">{{ old('deskripsi') }}</textarea>
                        <x-input-error class="mt-1" :messages="$errors->get('deskripsi')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-ui.label for="tahun_terbit">Tahun Terbit</x-ui.label>
                            <x-ui.input type="number" id="tahun_terbit" name="tahun_terbit" :value="old('tahun_terbit')"
                                required placeholder="Tahun Terbit Buku..." icon="ri-calendar-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('tahun_terbit')" />
                        </div>
                        <div>
                            <x-ui.label for="stok">Stok Buku</x-ui.label>
                            <x-ui.input type="number" id="stok" name="stok" :value="old('stok')" required
                                placeholder="Jumlah Stok..." icon="ri-stack-line" />
                            <x-input-error class="mt-1" :messages="$errors->get('stok')" />
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <x-ui.button href="{{ route('admin.books.index') }}" variant="outline">Batal</x-ui.button>
                    <x-ui.button type="submit">
                        <i class="ri-save-line mr-1"></i> Simpan Buku
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-app-layout>