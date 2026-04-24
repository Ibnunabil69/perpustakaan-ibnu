<x-app-layout>
    <x-slot name="header">Data Buku</x-slot>

    @php
        $currentUrl = route('admin.books.index');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-5">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Koleksi Buku</h3>
            <p class="text-sm text-gray-500">Kelola data buku, stok, dan kategori perpustakaan</p>
        </div>

        <x-ui.card>
            <div class="px-5 py-4 border-b border-amber-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full">
                    <form action="{{ $currentUrl }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2 flex-1">
                        @foreach(request()->except(['search', 'category_id', 'stock_status', 'per_page', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        
                        <div class="relative flex-1 md:w-64">
                            <x-ui.input type="text" name="search" :value="$search ?? ''" placeholder="Cari judul atau penulis..." class="!pl-9 w-full" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <x-ui.select name="category_id" class="w-full md:w-40" :value="request('category_id')" placeholder="Kategori" onchange="this.form.submit()">
                                <x-ui.select-option value="">Semua Kategori</x-ui.select-option>
                                @foreach ($categories as $cat)
                                    <x-ui.select-option value="{{ $cat->id }}">{{ $cat->name }}</x-ui.select-option>
                                @endforeach
                            </x-ui.select>

                            <x-ui.select name="stock_status" class="w-full md:w-32" :value="request('stock_status')" placeholder="Stok" onchange="this.form.submit()">
                                <x-ui.select-option value="">Semua Stok</x-ui.select-option>
                                <x-ui.select-option value="available">Tersedia</x-ui.select-option>
                                <x-ui.select-option value="out_of_stock">Habis</x-ui.select-option>
                            </x-ui.select>

                            <x-ui.select name="per_page" class="w-20" :value="$perPage" onchange="this.form.submit()">
                                <x-ui.select-option value="10">10</x-ui.select-option>
                                <x-ui.select-option value="25">25</x-ui.select-option>
                                <x-ui.select-option value="50">50</x-ui.select-option>
                            </x-ui.select>

                            <x-ui.button type="submit" variant="primary" size="icon" class="flex-shrink-0" title="Filter">
                                <i class="ri-filter-3-line"></i>
                            </x-ui.button>
                        </div>
                    </form>

                    <div class="hidden md:block w-px h-8 bg-amber-100 mx-1"></div>

                    <x-ui.button href="{{ route('admin.books.create') }}" class="flex-shrink-0">
                        <i class="ri-add-line mr-1.5"></i> Baru
                    </x-ui.button>
                </div>
            </div>
            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th class="w-16">Sampul</x-ui.th>
                    <x-ui.th>
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'judul', 'dir' => ($sortBy === 'judul' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Judul & Info
                            @if($sortBy === 'judul')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th>Kategori</x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'tahun_terbit', 'dir' => ($sortBy === 'tahun_terbit' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Tahun
                            @if($sortBy === 'tahun_terbit')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'stok', 'dir' => ($sortBy === 'stok' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Stok
                            @if($sortBy === 'stok')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="right">Aksi</x-ui.th>
                </x-slot>
                
                @forelse ($books as $book)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div class="w-10 h-14 rounded-lg bg-gray-50 border border-gray-200 overflow-hidden shadow-sm flex items-center justify-center">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ri-book-3-line text-gray-300 text-xl"></i>
                                @endif
                            </div>
                        </x-ui.td>
                        <x-ui.td>
                            <div>
                                <div class="font-semibold text-gray-900 leading-tight">{{ $book->judul }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $book->penulis }} · {{ $book->penerbit }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td>
                            @if($book->category)
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-md uppercase border border-amber-100">
                                    {{ $book->category->name }}
                                </span>
                            @else
                                <span class="text-[10px] text-gray-300 italic">No Category</span>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="center" class="text-sm text-gray-600">{{ $book->tahun_terbit }}</x-ui.td>
                        <x-ui.td align="center">
                            @if($book->stok <= 0)
                                <x-ui.badge color="rose">Habis</x-ui.badge>
                            @elseif($book->stok <= 5)
                                <x-ui.badge color="amber">{{ $book->stok }} Tersisa</x-ui.badge>
                            @else
                                <x-ui.badge color="emerald">{{ $book->stok }} Stok</x-ui.badge>
                            @endif
                        </x-ui.td>

                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button href="{{ route('admin.books.edit', $book->id) }}" variant="action" size="icon" title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </x-ui.button>
                                @if($book->active_loans_count > 0)
                                    <div class="relative group">
                                        <x-ui.button type="button" variant="outline" size="icon" class="opacity-50 cursor-not-allowed !bg-gray-100 !border-gray-200 !text-gray-400">
                                            <i class="ri-lock-2-line"></i>
                                        </x-ui.button>
                                        <!-- Mini Tooltip -->
                                        <div class="absolute bottom-full mb-2 right-0 hidden group-hover:block z-50">
                                            <div class="bg-gray-800 text-white text-[10px] py-1 px-2 rounded shadow-lg whitespace-nowrap">
                                                Sedang dipinjam ({{ $book->active_loans_count }})
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <x-ui.button type="button" variant="danger-action" size="icon" title="Hapus"
                                        @click="$dispatch('open-modal', { name: 'delete-book-{{ $book->id }}' })">
                                        <i class="ri-delete-bin-line"></i>
                                    </x-ui.button>

                                    @push('modals')
                                        <x-ui.modal name="delete-book-{{ $book->id }}" title="Hapus Buku">
                                            <div class="space-y-3">
                                                <div class="w-16 h-16 bg-rose-50 rounded-full flex items-center justify-center mx-auto text-rose-500 mb-4">
                                                    <i class="ri-book-3-line text-3xl"></i>
                                                </div>
                                                <p class="text-sm text-gray-600 text-center">
                                                    Hapus data buku <span class="font-bold text-gray-800">"{{ $book->judul }}"</span>?
                                                    <br>
                                                    <span class="text-[10px] text-gray-400 mt-2 block italic">Tindakan ini tidak dapat dibatalkan.</span>
                                                </p>
                                            </div>
                                            <x-slot name="footer">
                                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <x-ui.button type="submit" variant="danger">Hapus Permanen</x-ui.button>
                                                </form>
                                                <x-ui.button @click="$dispatch('close-modal', { name: 'delete-book-{{ $book->id }}' })" variant="outline">Batal</x-ui.button>
                                            </x-slot>
                                        </x-ui.modal>
                                    @endpush
                                @endif
                            </div>
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state 
                        icon="ri-inbox-line" 
                        title="Tidak ada buku ditemukan" 
                        description="Coba ubah kata kunci atau filter stok."
                        colspan="7"
                    />
                @endforelse
            </x-ui.table>

            @if($books->hasPages() || $books->total() > 0)
                <div class="px-5 py-4 bg-gray-50/50 border-t border-amber-50">
                    {{ $books->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
