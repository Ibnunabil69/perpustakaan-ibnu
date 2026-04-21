<x-app-layout>
    <x-slot name="header">Riwayat Peminjaman</x-slot>

    @php
        $currentUrl = route('siswa.peminjaman.riwayat');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-4">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-wrap">
            <form action="{{ $currentUrl }}" method="GET" class="flex items-center gap-2">
                @foreach(request()->except(['status', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <x-ui.select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ ($status ?? '') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="dikembalikan" {{ ($status ?? '') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </x-ui.select>
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

        <x-ui.card>
            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th>Buku</x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'tanggal_pinjam', 'dir' => ($sortBy === 'tanggal_pinjam' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Tgl Pinjam
                            @if($sortBy === 'tanggal_pinjam')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="center">Tgl Kembali</x-ui.th>
                    <x-ui.th align="center">
                        <a href="{{ $currentUrl . '?' . http_build_query(array_merge($queryParams, ['sort' => 'status', 'dir' => ($sortBy === 'status' && $sortDir === 'asc') ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-amber-600 transition-colors">
                            Status
                            @if($sortBy === 'status')
                                <i class="ri-arrow-{{ $sortDir === 'asc' ? 'up' : 'down' }}-s-line text-amber-500"></i>
                            @endif
                        </a>
                    </x-ui.th>
                    <x-ui.th align="right">Aksi</x-ui.th>
                </x-slot>

                @forelse ($peminjamans as $peminjaman)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div>
                                <div class="font-medium text-gray-800">{{ $peminjaman->book->judul }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $peminjaman->book->penulis }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span class="text-sm text-gray-500">{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '—' }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            @if($peminjaman->status === 'dipinjam')
                                <x-ui.badge color="amber">Dipinjam</x-ui.badge>
                            @else
                                <x-ui.badge color="emerald">Selesai</x-ui.badge>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="right">
                            @if($peminjaman->status === 'dipinjam')
                                <form action="{{ route('siswa.peminjaman.kembalikan', $peminjaman->id) }}" method="POST" class="inline" onsubmit="return confirm('Kembalikan buku ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <x-ui.button type="submit" size="sm">Kembalikan</x-ui.button>
                                </form>
                            @else
                                <span class="text-xs text-emerald-600 font-medium flex items-center justify-end gap-1">
                                    <i class="ri-check-line"></i> Dikembalikan
                                </span>
                            @endif
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state icon="ri-history-line" title="Belum ada riwayat peminjaman" colspan="5" />
                @endforelse
            </x-ui.table>

            @if($peminjamans->hasPages() || $peminjamans->total() > 0)
                <div class="px-4 py-3 border-t border-amber-100 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ $peminjamans->firstItem() ?? 0 }}–{{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} data
                    </p>
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
