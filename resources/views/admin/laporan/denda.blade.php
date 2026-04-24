<x-app-layout>
    <x-slot name="header">Laporan Denda</x-slot>

    <div class="space-y-5">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Total Denda Summary -->
            <div
                class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl text-white shadow-md overflow-hidden relative group border border-white/10">
                <div
                    class="absolute -right-4 -top-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="ri-money-dollar-circle-line text-7xl"></i>
                </div>
                <div class="p-4 relative z-10">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-bold text-rose-100 uppercase tracking-widest opacity-80">Total Pendapatan Denda</p>
                            <h3 class="text-3xl font-black mt-0.5 whitespace-nowrap tracking-tight">
                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                            </h3>
                            <div class="flex items-center gap-1.5 mt-2 text-rose-100/70">
                                <i class="ri-refresh-line text-[10px]"></i>
                                <p class="text-[10px] font-medium tracking-wide">Dari {{ $totalTransactions }} transaksi selesai</p>
                            </div>
                        </div>
                        <div
                            class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-inner group-hover:rotate-12 transition-transform">
                            <i class="ri-wallet-3-line text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Fine Setting -->
            <div class="bg-white rounded-2xl border {{ $activeLoansCount > 0 ? 'border-gray-100 bg-gray-50/50' : 'border-amber-100' }} shadow-sm p-4 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:scale-110 transition-transform duration-500">
                    <i class="ri-settings-4-line text-8xl text-amber-600"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row md:items-end gap-4">
                        <div class="flex-grow">
                            <label class="block text-[10px] font-black text-amber-500 uppercase tracking-widest mb-1.5 {{ $activeLoansCount > 0 ? 'opacity-50' : '' }}">Pengaturan Denda Masal</label>
                            <div class="relative group/input flex items-center">
                                <span class="absolute left-3.5 z-20 pointer-events-none font-bold text-sm text-gray-900">Rp</span>
                                <input type="number" id="input_denda_per_hari" value="{{ $dendaPerHari }}" 
                                    class="block w-full pl-10 pr-3 py-2 bg-white border border-amber-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all shadow-inner {{ $activeLoansCount > 0 ? 'bg-gray-100 cursor-not-allowed opacity-50' : 'bg-white' }}" 
                                    placeholder="Contoh: 1000" {{ $activeLoansCount > 0 ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if($activeLoansCount > 0)
                                <div class="relative group/tooltip">
                                    <x-ui.button type="button" variant="outline" class="w-full md:w-auto h-[38px] px-6 cursor-not-allowed opacity-50 border-gray-200 text-gray-400">
                                        <i class="ri-lock-2-line mr-2"></i> Simpan Perubahan
                                    </x-ui.button>
                                    <div class="absolute bottom-full mb-2 left-1/2 -translate-x-1/2 hidden group-hover/tooltip:block z-50">
                                        <div class="bg-gray-800 text-white text-[10px] py-1 px-3 rounded shadow-lg whitespace-nowrap">
                                            Selesaikan {{ $activeLoansCount }} peminjaman aktif dulu
                                        </div>
                                    </div>
                                </div>
                            @else
                                <x-ui.button type="button" @click="$dispatch('open-modal', { name: 'confirm-change-denda' })" variant="primary" class="w-full md:w-auto h-[38px] px-6 shadow-md shadow-amber-500/20">
                                    <i class="ri-save-line mr-2"></i> Simpan Perubahan
                                </x-ui.button>
                            @endif
                        </div>
                    </div>
                    @if($activeLoansCount > 0)
                        <p class="text-[9px] text-rose-500 mt-2 font-bold flex items-center gap-1.5">
                            <i class="ri-error-warning-line"></i>
                            Fitur terkunci karena masih ada peminjaman yang berlangsung demi keadilan sistem.
                        </p>
                    @else
                        <p class="text-[10px] text-gray-400 mt-2 italic">* Nilai ini akan digunakan untuk semua buku secara otomatis.</p>
                    @endif
                </div>

                <!-- Modal Konfirmasi Ganti Denda -->
                @push('modals')
                <x-ui.modal name="confirm-change-denda" title="Konfirmasi Perubahan Denda">
                    <form action="{{ route('admin.settings.update_denda') }}" method="POST">
                        @csrf
                        <input type="hidden" name="denda_per_hari" id="hidden_denda_per_hari">
                        
                        <div class="space-y-4">
                            <div class="flex items-start gap-3 p-4 bg-amber-50 rounded-xl border border-amber-100">
                                <i class="ri-error-warning-fill text-2xl text-amber-500"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-amber-900">Apakah Anda yakin?</h4>
                                    <p class="text-xs text-amber-700 leading-relaxed mt-1">
                                        Perubahan tarif denda akan langsung berdampak pada seluruh perhitungan denda pengembalian buku di masa mendatang.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-2">
                                <x-ui.button type="button" @click="$dispatch('close-modal', { name: 'confirm-change-denda' })" variant="outline">
                                    Batal
                                </x-ui.button>
                                <x-ui.button type="submit" variant="primary" onclick="document.getElementById('hidden_denda_per_hari').value = document.getElementById('input_denda_per_hari').value">
                                    Ya, Simpan Perubahan
                                </x-ui.button>
                            </div>
                        </div>
                    </form>
                </x-ui.modal>
                @endpush
            </div>
        </div>

        <div class="mt-8 mb-5">
            <h3 class="text-lg font-bold text-gray-800">Laporan Denda</h3>
            <p class="text-sm text-gray-500">Detail transaksi pengembalian buku yang memiliki denda</p>
        </div>

        <x-ui.card>
            <div class="px-5 py-4 border-b border-amber-50 flex flex-col xl:flex-row xl:items-center justify-between gap-4">

                <form action="{{ route('admin.laporan.denda') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3 no-print w-full xl:w-auto">
                    <div class="flex items-center gap-2 flex-1">
                        <div class="relative flex-1">
                            <x-ui.input type="date" id="from" name="from" :value="$from" :max="$today" class="w-full !pl-3" />
                        </div>
                        <span class="text-gray-400 text-xs font-bold uppercase">s.d</span>
                        <div class="relative flex-1">
                            <x-ui.input type="date" id="to" name="to" :value="$to" :max="$today" class="w-full !pl-3" />
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 justify-end">
                        <x-ui.button type="submit" variant="primary" size="icon" title="Filter">
                            <i class="ri-filter-3-line"></i>
                        </x-ui.button>
                        @if($from || $to)
                            <x-ui.button href="{{ route('admin.laporan.denda') }}" variant="outline" size="icon" title="Reset Filter">
                                <i class="ri-refresh-line"></i>
                            </x-ui.button>
                        @endif
                        <div class="w-px h-8 bg-amber-100 mx-1"></div>
                        <x-ui.button href="{{ route('admin.laporan.denda.print', ['from' => $from, 'to' => $to]) }}" target="_blank" variant="outline" class="whitespace-nowrap">
                            <i class="ri-printer-line mr-1.5"></i> Cetak
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <x-ui.table>
                <x-slot name="head">
                    <x-ui.th>Anggota</x-ui.th>
                    <x-ui.th>Buku</x-ui.th>
                    <x-ui.th align="center">Tgl Pinjam</x-ui.th>
                    <x-ui.th align="center">Tgl Kembali</x-ui.th>
                    <x-ui.th align="center">Keterlambatan</x-ui.th>
                    <x-ui.th align="right">Denda</x-ui.th>
                </x-slot>

                @forelse ($peminjamans as $p)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <x-ui.td>
                            <div class="font-semibold text-gray-900 leading-tight">{{ $p->user->name }}</div>
                        </x-ui.td>
                        <x-ui.td>
                            <div class="text-sm text-gray-700 font-medium">{{ $p->book->judul }}</div>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span
                                class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            <span
                                class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</span>
                        </x-ui.td>
                        <x-ui.td align="center">
                            @php
                                $target = \Carbon\Carbon::parse($p->tanggal_kembali_target);
                                $kembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                                $diff = $target->diffInDays($kembali);
                            @endphp
                            <span class="text-sm text-gray-600">{{ $diff }} Hari</span>
                        </x-ui.td>
                        <x-ui.td align="right">
                            <span class="font-bold text-rose-600">Rp
                                {{ number_format($p->total_denda, 0, ',', '.') }}</span>
                        </x-ui.td>
                    </tr>
                @empty
                    <x-ui.empty-state icon="ri-money-dollar-circle-line" title="Belum ada data denda" colspan="6" />
                @endforelse
            </x-ui.table>

            @if($peminjamans->hasPages())
                <div class="px-5 py-4 bg-gray-50/50 border-t border-amber-50">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>