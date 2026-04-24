<x-app-layout>
    <x-slot name="header">Transaksi Peminjaman</x-slot>

    @php
        $currentUrl = route('admin.peminjaman.index');
        $queryParams = request()->except(['sort', 'dir', 'page']);
    @endphp

    <div class="space-y-5">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Transaksi Peminjaman</h3>
            <p class="text-sm text-gray-500">Pantau dan kelola sirkulasi buku perpustakaan</p>
        </div>

        <x-ui.card>
            <div class="px-5 py-4 border-b border-amber-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full">
                    <form action="{{ $currentUrl }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-2 flex-1">
                        @foreach(request()->except(['search', 'status', 'per_page', 'page']) as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        
                        <div class="relative flex-1 md:w-64">
                            <x-ui.input type="text" name="search" :value="$search ?? ''" placeholder="Cari peminjam atau buku..." class="!pl-9 w-full" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <x-ui.select name="status" class="w-full md:w-40" :value="request('status')" placeholder="Semua Status" onchange="this.form.submit()">
                                <x-ui.select-option value="">Semua Status</x-ui.select-option>
                                <x-ui.select-option value="menunggu">Menunggu</x-ui.select-option>
                                <x-ui.select-option value="disetujui">Dipinjam</x-ui.select-option>
                                <x-ui.select-option value="ditolak">Ditolak</x-ui.select-option>
                                <x-ui.select-option value="menunggu_kembali">Menunggu Kembali</x-ui.select-option>
                                <x-ui.select-option value="dikembalikan">Selesai</x-ui.select-option>
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
                </div>
            </div>
            @php
                $globalDenda = \App\Models\Setting::get('denda_per_hari', 1000);
            @endphp
            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th>Peminjam & Buku</x-ui.th>
                    <x-ui.th align="center">Tgl Pinjam</x-ui.th>
                    <x-ui.th align="center">Target Kembali</x-ui.th>
                    <x-ui.th align="center">Status</x-ui.th>
                    <x-ui.th align="right">Denda</x-ui.th>
                    <x-ui.th align="right">Aksi</x-ui.th>
                </x-slot>

                @forelse ($peminjamans as $peminjaman)
                    @php
                        $statusClean = trim(strtolower($peminjaman->status));
                        $isLate = false;
                        $potentialDenda = 0;
                        $hari = 0;
                        
                        if (($statusClean === 'disetujui' || $statusClean === 'menunggu_kembali') && $peminjaman->tanggal_kembali_target) {
                            $targetDate = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->startOfDay();
                            $todayDate = \Carbon\Carbon::today();
                            
                            if ($todayDate->gt($targetDate)) {
                                $isLate = true;
                                $hari = $todayDate->diffInDays($targetDate);
                                $potentialDenda = abs($hari) * $globalDenda;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div>
                                <div class="font-semibold text-gray-900 leading-tight">{{ $peminjaman->user->name }}</div>
                                <div class="text-xs text-amber-600 font-medium mt-0.5">{{ $peminjaman->book->judul }}</div>
                            </div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <div class="text-sm {{ $isLate ? 'text-rose-600 font-bold' : 'text-gray-600' }}">
                                {{ $peminjaman->tanggal_kembali_target ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->format('d/m/Y') : '-' }}
                            </div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            @if($peminjaman->status === 'menunggu')
                                <x-ui.badge color="sky">Menunggu Pinjam</x-ui.badge>
                            @elseif($peminjaman->status === 'disetujui')
                                <x-ui.badge color="amber">Dipinjam</x-ui.badge>
                            @elseif($peminjaman->status === 'menunggu_kembali')
                                <x-ui.badge color="indigo">Menunggu Kembali</x-ui.badge>
                            @elseif($peminjaman->status === 'ditolak')
                                <x-ui.badge color="red">Ditolak</x-ui.badge>
                            @else
                                <x-ui.badge color="emerald">Selesai</x-ui.badge>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="right">
                            @if($peminjaman->status === 'dikembalikan')
                                <span class="text-sm font-bold text-gray-700">Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}</span>
                            @elseif(in_array($peminjaman->status, ['disetujui', 'menunggu_kembali']))
                                @if($isLate && $potentialDenda > 0)
                                    <div class="flex flex-col items-end">
                                        <span class="text-sm font-black text-rose-600">Rp {{ number_format($potentialDenda, 0, ',', '.') }}</span>
                                        <span class="text-[9px] text-rose-400 font-medium">{{ abs($hari) }} hari x Rp {{ number_format($globalDenda, 0, ',', '.') }}</span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-end">
                                        <span class="text-xs text-gray-400 font-medium italic">Belum kembali</span>
                                    </div>
                                @endif
                            @else
                                <div class="flex flex-col items-end">
                                    <span class="text-gray-300 font-normal italic">-</span>
                                </div>
                            @endif
                        </x-ui.td>
                        <x-ui.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Group Aksi Pinjam: Centralized in Detail Modal -->
                                @if($peminjaman->status === 'menunggu')
                                    <x-ui.button @click="$dispatch('open-modal', { name: 'process-loan-{{ $peminjaman->id }}' })" variant="primary" size="sm">
                                        Detail & Proses
                                    </x-ui.button>
                                @endif

                                <!-- Group Aksi Kembali -->
                                @if($peminjaman->status === 'menunggu_kembali')
                                    <x-ui.button @click="$dispatch('open-modal', { name: 'approve-return-{{ $peminjaman->id }}' })" variant="emerald" size="sm">Konfirmasi Kembali</x-ui.button>
                                @endif

                                @if($peminjaman->status === 'disetujui')
                                    <x-ui.button @click="$dispatch('open-modal', { name: 'direct-return-{{ $peminjaman->id }}' })" variant="secondary" size="sm">Kembalikan</x-ui.button>
                                @endif

                                <!-- Admin Action: Delete (Only for REJECTED records to clean up logs/spam) -->
                                @if($peminjaman->status === 'ditolak')
                                    <x-ui.button @click="$dispatch('open-modal', { name: 'delete-loan-{{ $peminjaman->id }}' })" 
                                                 variant="danger-action" 
                                                 size="icon" 
                                                 title="Hapus Riwayat"
                                                 class="opacity-40 hover:opacity-100 transition-opacity">
                                        <i class="ri-delete-bin-line"></i>
                                    </x-ui.button>

                                    @push('modals')
                                        <x-ui.modal name="delete-loan-{{ $peminjaman->id }}" title="Hapus Riwayat Permanen">
                                            <div class="space-y-3">
                                                <p class="text-sm text-gray-600">Anda yakin ingin menghapus riwayat pengajuan <span class="font-bold text-gray-900">"{{ $peminjaman->book->judul }}"</span> oleh <span class="font-bold text-gray-900">{{ $peminjaman->user->name }}</span>?</p>
                                                <div class="p-3 bg-rose-50 rounded-lg border border-rose-100 text-[11px] text-rose-700 leading-relaxed">
                                                    <i class="ri-error-warning-line"></i> Data ini akan dihapus selamanya dari database dan tidak dapat dikembalikan.
                                                </div>
                                            </div>
                                            <x-slot name="footer">
                                                <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <x-ui.button type="submit" variant="danger">Ya, Hapus Permanen</x-ui.button>
                                                </form>
                                                <x-ui.button @click="$dispatch('close-modal', { name: 'delete-loan-{{ $peminjaman->id }}' })" variant="outline">Batal</x-ui.button>
                                            </x-slot>
                                        </x-ui.modal>
                                    @endpush
                                @endif
                            </div>

                            <!-- Modals Section -->
                            @push('modals')
                                <!-- Modal Detail & Proses Pinjam -->
                                <x-ui.modal name="process-loan-{{ $peminjaman->id }}" title="Detail & Proses Pengajuan">
                                    <form action="{{ route('admin.peminjaman.approve', $peminjaman->id) }}" method="POST" class="space-y-4">
                                        @csrf @method('PATCH')
                                        
                                        <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-100">
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Peminjam</label>
                                                <p class="text-sm font-bold text-gray-800">{{ $peminjaman->user->name }}</p>
                                            </div>
                                            <div>
                                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Buku</label>
                                                <p class="text-sm font-bold text-amber-600">{{ $peminjaman->book->judul }}</p>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <label for="target_kembali_{{ $peminjaman->id }}" class="text-xs font-bold text-gray-700">Tentukan Tanggal Kembali (Maks 7 Hari)</label>
                                            <x-ui.input 
                                                id="target_kembali_{{ $peminjaman->id }}" 
                                                type="date" 
                                                name="tanggal_kembali_target" 
                                                :value="\Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->format('Y-m-d')" 
                                                min="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('Y-m-d') }}"
                                                max="{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->addDays(7)->format('Y-m-d') }}"
                                                required
                                            />
                                            <div class="flex items-center justify-between text-[10px] font-medium">
                                                <p class="text-gray-400 italic">* Diajukan: {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->format('d M Y') }}</p>
                                                <p class="text-amber-600 font-bold">Batas Maks: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->addDays(7)->format('d M Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                                            <p class="text-xs text-amber-800 leading-relaxed">
                                                <i class="ri-information-line"></i> Anda dapat mengubah tanggal kembali di atas jika diperlukan sebelum menekan tombol <strong>Setujui</strong>.
                                            </p>
                                        </div>

                                        <div class="flex items-center justify-between pt-4 gap-3">
                                            <div class="flex gap-2">
                                                <x-ui.button type="submit" variant="emerald">Setujui Pinjaman</x-ui.button>
                                                <x-ui.button type="button" @click="$dispatch('close-modal', { name: 'process-loan-{{ $peminjaman->id }}' }); $dispatch('open-modal', { name: 'reject-loan-{{ $peminjaman->id }}' })" variant="danger">
                                                    Tolak
                                                </x-ui.button>
                                            </div>
                                            <x-ui.button type="button" @click="$dispatch('close-modal', { name: 'process-loan-{{ $peminjaman->id }}' })" variant="outline">Batal</x-ui.button>
                                        </div>
                                    </form>
                                </x-ui.modal>

                                <!-- Modal Reject Pinjam (Separate for safety) -->
                                <x-ui.modal name="reject-loan-{{ $peminjaman->id }}" title="Konfirmasi Penolakan">
                                    <div class="space-y-3">
                                        <p class="text-sm text-gray-600">Anda yakin ingin menolak pengajuan dari <span class="font-bold">{{ $peminjaman->user->name }}</span>?</p>
                                    </div>
                                    <x-slot name="footer">
                                        <form action="{{ route('admin.peminjaman.reject', $peminjaman->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <x-ui.button type="submit" variant="danger">Ya, Tolak Pengajuan</x-ui.button>
                                        </form>
                                        <x-ui.button @click="$dispatch('close-modal', { name: 'reject-loan-{{ $peminjaman->id }}' }); $dispatch('open-modal', { name: 'process-loan-{{ $peminjaman->id }}' })" variant="outline">Kembali</x-ui.button>
                                    </x-slot>
                                </x-ui.modal>

                                <!-- Modal Approve Kembali -->
                                <x-ui.modal name="approve-return-{{ $peminjaman->id }}" title="Konfirmasi Pengembalian">
                                    <div class="space-y-4">
                                        <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold text-gray-400">DETAIL DENDA</label>
                                            @if($potentialDenda > 0)
                                                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex flex-col">
                                                            <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Informasi Denda</span>
                                                            <span class="text-xs text-rose-700 font-bold">Terlambat {{ $hari }} Hari</span>
                                                        </div>
                                                        <div class="text-right">
                                                            <span class="text-lg font-black text-rose-600 tracking-tight">Rp {{ number_format($potentialDenda, 0, ',', '.') }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-sm font-medium">
                                                    Tepat waktu. Tidak ada denda.
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">Konfirmasi bahwa buku telah diterima kembali dalam kondisi baik?</p>
                                    </div>
                                    <x-slot name="footer">
                                        <form action="{{ route('admin.peminjaman.approve_kembali', $peminjaman->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <x-ui.button type="submit">Konfirmasi Penerimaan</x-ui.button>
                                        </form>
                                        <x-ui.button @click="$dispatch('close-modal', { name: 'approve-return-{{ $peminjaman->id }}' })" variant="outline">Batal</x-ui.button>
                                    </x-slot>
                                </x-ui.modal>

                                <!-- Modal Direct Return -->
                                <x-ui.modal name="direct-return-{{ $peminjaman->id }}" title="Pengecekan Pengembalian">
                                    <div class="space-y-4">
                                         <div class="flex flex-col gap-1">
                                            <label class="text-xs font-bold text-gray-400">ESTIMASI DENDA</label>
                                            @if($potentialDenda > 0)
                                                <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-800 font-bold">
                                                    Rp {{ number_format($potentialDenda, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-800 text-sm">
                                                    Tidak ada denda.
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">Proses pengembalian buku tanpa menunggu pengajuan anggota?</p>
                                    </div>
                                    <x-slot name="footer">
                                        <form action="{{ route('admin.peminjaman.kembali', $peminjaman->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <x-ui.button type="submit" variant="secondary">Ya, Kembalikan</x-ui.button>
                                        </form>
                                        <x-ui.button @click="$dispatch('close-modal', { name: 'direct-return-{{ $peminjaman->id }}' })" variant="outline">Batal</x-ui.button>
                                    </x-slot>
                                </x-ui.modal>
                            @endpush

                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state icon="ri-exchange-line" title="Belum ada transaksi" colspan="6" />
                @endforelse
            </x-ui.table>

            @if($peminjamans->hasPages() || $peminjamans->total() > 0)
                <div class="px-5 py-4 bg-gray-50/50 border-t border-amber-50">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>
