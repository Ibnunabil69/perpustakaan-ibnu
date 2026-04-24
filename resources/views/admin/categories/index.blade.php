<x-app-layout>
    <x-slot name="header">Kelola Kategori Buku</x-slot>

    <div class="space-y-5">
        <!-- Header Section -->
        <div>
            <h3 class="text-lg font-bold text-gray-800">Manajemen Kategori</h3>
            <p class="text-sm text-gray-500">Kelola kategori untuk pengelompokan koleksi buku perpustakaan</p>
        </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Add Category Form (Card) -->
            <div class="lg:col-span-1">
                <x-ui.card>
                    <div class="px-5 py-2.5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Tambah Kategori</h3>
                    </div>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="px-5 pt-3 pb-5 space-y-5" x-data="{ color: '#475569' }">
                        @csrf
                        <div>
                            <x-ui.label for="name">Nama Kategori</x-ui.label>
                            <x-ui.input id="name" name="name" type="text" placeholder="Contoh: Novel, Sains, Religi..." required />
                        </div>
                        
                        <div>
                            <x-ui.label>Pilih Warna Label</x-ui.label>
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                                <!-- Main Picker & Info -->
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0 w-10 h-10 rounded-lg overflow-hidden border-2 border-white shadow-sm">
                                        <input type="color" id="color" name="color" x-model="color"
                                            class="absolute -inset-2 w-[150%] h-[150%] cursor-pointer border-none bg-transparent">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Warna Kustom</span>
                                        <span class="text-[11px] font-mono font-bold text-gray-600" x-text="color"></span>
                                    </div>
                                </div>
                                
                                <!-- Curated Presets (Darker shades for better contrast with white text) -->
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="c in ['#475569', '#991b1b', '#c2410c', '#b45309', '#065f46', '#1e40af', '#3730a3', '#6b21a8', '#9f1239']">
                                        <button type="button" @click="color = c" 
                                            class="w-6 h-6 rounded-md border border-white shadow-sm transition-transform hover:scale-110 focus:ring-2 focus:ring-amber-400 outline-none"
                                            :style="'background-color: ' + c"
                                            :class="color === c ? 'ring-2 ring-amber-500 scale-110' : ''">
                                        </button>
                                    </template>
                                </div>
                                <p class="text-[10px] text-gray-400 italic">Tips: Gunakan warna bold agar teks putih di kartu siswa terbaca jelas.</p>
                            </div>
                        </div>

                        <x-ui.button type="submit" class="w-full">
                            <i class="ri-add-line mr-1.5"></i> Simpan Kategori
                        </x-ui.button>
                    </form>
                </x-ui.card>
            </div>

            <!-- Category List -->
            <div class="lg:col-span-2">
                <x-ui.card>
                    <div class="px-5 py-2.5 border-b border-amber-50">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Kategori</h3>
                    </div>
                    <x-ui.table>
                        <x-slot name="head">
                            <x-ui.th>Nama Kategori</x-ui.th>
                            <x-ui.th align="center">Jumlah Buku</x-ui.th>
                            <x-ui.th align="right">Aksi</x-ui.th>
                        </x-slot>

                        @forelse ($categories as $category)
                            <tr class="hover:bg-amber-50/50 transition-colors"
                                x-data="{ editing: false, name: '{{ $category->name }}', editColor: '{{ $category->color ?? '#64748b' }}' }">
                                <x-ui.td>
                                    <template x-if="!editing">
                                        <div class="flex items-center gap-3">
                                            <div class="w-3.5 h-3.5 rounded-full shadow-sm ring-2 ring-white"
                                                style="background-color: {{ $category->color ?? '#64748b' }}"></div>
                                            <div class="font-bold text-gray-800 leading-tight">{{ $category->name }}</div>
                                        </div>
                                    </template>
                                    <template x-if="editing">
                                        <form id="edit-form-{{ $category->id }}"
                                            action="{{ route('admin.categories.update', $category->id) }}" method="POST"
                                            class="flex items-center gap-2">
                                            @csrf @method('PUT')
                                            <x-ui.input name="name" x-model="name" class="!h-9 flex-1" />
                                            <div class="relative w-9 h-9 rounded-lg overflow-hidden border border-gray-200">
                                                <input type="color" name="color" x-model="editColor"
                                                    class="absolute -inset-2 w-[150%] h-[150%] cursor-pointer border-none bg-transparent">
                                            </div>
                                            <x-ui.button type="submit" size="sm" variant="emerald">OK</x-ui.button>
                                            <x-ui.button type="button" size="sm" variant="outline"
                                                @click="editing = false"><i class="ri-close-line"></i></x-ui.button>
                                        </form>
                                    </template>
                                </x-ui.td>
                                <x-ui.td align="center">
                                    <x-ui.badge color="amber" class="!px-3">{{ $category->books_count }} Buku</x-ui.badge>
                                </x-ui.td>
                                <x-ui.td align="right">
                                    <div class="flex items-center justify-end gap-1" x-show="!editing">
                                        <x-ui.button @click="editing = true" variant="action" size="icon" title="Edit">
                                            <i class="ri-edit-2-line"></i>
                                        </x-ui.button>
                                        <x-ui.button type="button" variant="danger-action" size="icon" title="Hapus"
                                            @click="$dispatch('open-modal', { name: 'delete-category-{{ $category->id }}' })">
                                            <i class="ri-delete-bin-line"></i>
                                        </x-ui.button>

                                        @push('modals')
                                            <x-ui.modal name="delete-category-{{ $category->id }}" title="Konfirmasi Hapus Kategori">
                                                <div class="space-y-3">
                                                    <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-500 mb-4">
                                                        <i class="ri-delete-bin-line text-3xl"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-600 text-center">
                                                        Anda yakin ingin menghapus kategori <span class="font-bold text-gray-800">"{{ $category->name }}"</span>?
                                                        <br>
                                                        <span class="text-[10px] text-rose-500 mt-2 block">Buku yang terkait akan menjadi "Tanpa Kategori".</span>
                                                    </p>
                                                </div>
                                                <x-slot name="footer">
                                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <x-ui.button type="submit" variant="danger">Ya, Hapus!</x-ui.button>
                                                    </form>
                                                    <x-ui.button @click="$dispatch('close-modal', { name: 'delete-category-{{ $category->id }}' })" variant="outline">Batal</x-ui.button>
                                                </x-slot>
                                            </x-ui.modal>
                                        @endpush
                                    </div>
                                </x-ui.td>
                            </tr>
                        @empty
                            <x-ui.empty-state icon="ri-bookmark-3-line" title="Belum ada kategori" colspan="3" />
                        @endforelse
                    </x-ui.table>
                </x-ui.card>
            </div>
        </div>
    </div>
</x-app-layout>