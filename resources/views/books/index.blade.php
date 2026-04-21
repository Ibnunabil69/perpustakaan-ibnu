<x-app-layout>
    <x-slot name="header">Data Buku</x-slot>

    @php
        $currentUrl = route('admin.books.index');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-4">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <form action="{{ $currentUrl }}" method="GET" class="w-full sm:w-64">
                    @foreach(request()->except(['search', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <x-ui.input type="text" name="search" :value="$search ?? ''" placeholder="Cari buku..." icon="ri-search-2-line" />
                </form>

                <form action="{{ $currentUrl }}" method="GET" class="flex items-center gap-2">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <x-ui.select name="per_page" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </x-ui.select>
                </form>
            </div>

            <x-ui.button href="{{ route('admin.books.create') }}">
                <i class="ri-add-line"></i> Tambah Buku
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th>
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'judul', 'dir' => ($sortBy === 'judul' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Buku
                            @if($sortBy === 'judul')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
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
                            <div>
                                <div class="font-medium text-gray-800">{{ $book->judul }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $book->penulis }} · {{ $book->penerbit }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td align="center">{{ $book->tahun_terbit }}</x-ui.td>
                        <x-ui.td align="center">
                            @if($book->stok <= 0)
                                <x-ui.badge color="rose">Habis</x-ui.badge>
                            @elseif($book->stok <= 5)
                                <x-ui.badge color="amber">{{ $book->stok }}</x-ui.badge>
                            @else
                                <x-ui.badge color="emerald">{{ $book->stok }}</x-ui.badge>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button href="{{ route('admin.books.edit', $book->id) }}" variant="action" size="icon" title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </x-ui.button>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus buku ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger-action" size="icon" title="Hapus">
                                        <i class="ri-delete-bin-line"></i>
                                    </x-ui.button>
                                </form>
                            </div>
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state 
                        icon="ri-inbox-line" 
                        title="Tidak ada buku ditemukan" 
                        description="Coba ubah kata kunci pencarian."
                        colspan="4"
                    />
                @endforelse
            </x-ui.table>

            @if($books->hasPages() || $books->total() > 0)
                <div class="px-4 py-3 border-t border-amber-100 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $books->firstItem() ?? 0 }}–{{ $books->lastItem() ?? 0 }} dari {{ $books->total() }} data
                    </p>
                    {{ $books->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
