<x-app-layout>
    <x-slot name="header">Riwayat Peminjaman</x-slot>

    @php
        $currentUrl = route('siswa.peminjaman.riwayat');
        $queryParams = request()->except(['sort', 'dir', 'page']);
        $globalDenda = \App\Models\Setting::get('denda_per_hari', 1000);
    @endphp

    <div class="space-y-6 pb-10">
        <!-- Header & Filter -->
        <div class="space-y-3">
            <div>
                <h3 class="text-sm md:text-2xl font-bold text-gray-800">Rak Buku Virtual Anda</h3>
                <p class="text-[10px] md:text-xs text-gray-400 mt-0.5 font-medium">Lacak setiap perjalanan membacamu</p>
            </div>

            <!-- Search -->
            <form action="{{ $currentUrl }}" method="GET">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ri-search-2-line text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul buku di riwayatmu..."
                            class="block w-full pl-10 pr-3 py-2.5 bg-white border border-amber-200/80 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all shadow-sm">
                    </div>
                    <button type="submit" class="shrink-0 h-[42px] px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-sm font-bold shadow-sm shadow-amber-200/50 transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="ri-search-line"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('siswa.peminjaman.riwayat', request()->except('search', 'page')) }}" class="shrink-0 h-[42px] w-[42px] flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-rose-500 hover:border-rose-200 transition-all">
                            <i class="ri-close-line text-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Status Chips — Horizontal Scroll -->
            @php
                $statuses = [
                    '' => ['label' => 'Semua', 'icon' => 'ri-apps-line', 'color' => 'amber'],
                    'menunggu' => ['label' => 'Menunggu', 'icon' => 'ri-time-line', 'color' => 'sky'],
                    'disetujui' => ['label' => 'Dipinjam', 'icon' => 'ri-book-read-line', 'color' => 'amber'],
                    'menunggu_kembali' => ['label' => 'Ditinjau', 'icon' => 'ri-eye-line', 'color' => 'indigo'],
                    'ditolak' => ['label' => 'Ditolak', 'icon' => 'ri-close-circle-line', 'color' => 'rose'],
                    'dikembalikan' => ['label' => 'Selesai', 'icon' => 'ri-check-double-line', 'color' => 'emerald'],
                ];
                $activeStatus = request('status', '');
            @endphp
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-1 px-1">
                @foreach($statuses as $value => $meta)
                    @php
                        $isActive = $activeStatus === $value;
                        $activeClasses = match($meta['color']) {
                            'sky' => 'bg-sky-500 text-white shadow-sm shadow-sky-500/20',
                            'amber' => 'bg-amber-500 text-white shadow-sm shadow-amber-500/20',
                            'indigo' => 'bg-indigo-500 text-white shadow-sm shadow-indigo-500/20',
                            'rose' => 'bg-rose-500 text-white shadow-sm shadow-rose-500/20',
                            'emerald' => 'bg-emerald-500 text-white shadow-sm shadow-emerald-500/20',
                            default => 'bg-amber-500 text-white shadow-sm shadow-amber-500/20',
                        };
                    @endphp
                    <a href="{{ route('siswa.peminjaman.riwayat', array_merge(request()->except('status', 'page'), $value ? ['status' => $value] : [])) }}"
                        class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all duration-200 whitespace-nowrap {{ $isActive ? $activeClasses : 'bg-white text-gray-600 border border-gray-200 hover:border-' . $meta['color'] . '-300 hover:text-' . $meta['color'] . '-600' }}">
                        <i class="{{ $meta['icon'] }} text-sm"></i>
                        {{ $meta['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Responsive Card List -->
        <div class="grid grid-cols-1 gap-4">
            @forelse ($peminjamans as $peminjaman)
                @php
                    $isLate = false;
                    $potentialDenda = 0;
                    $hari = 0;
                    if (in_array($peminjaman->status, ['disetujui', 'menunggu_kembali'])) {
                        $target = \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->startOfDay();
                        $today = \Carbon\Carbon::now()->startOfDay();
                        if ($today->gt($target)) {
                            $isLate = true;
                            $hari = $today->diffInDays($target);
                            $potentialDenda = abs($hari) * $globalDenda;
                        }
                    }
                @endphp
                <div class="group relative bg-white rounded-2xl border border-gray-100 p-4 md:p-5 hover:shadow-xl hover:shadow-amber-500/5 transition-all duration-300">
                    <div class="flex flex-col md:flex-row gap-4 md:gap-5">
                        <!-- Book Cover Thumbnail -->
                        <div class="w-full md:w-20 h-32 md:h-28 shrink-0 rounded-lg overflow-hidden bg-gray-50 border border-gray-100 shadow-sm">
                            @if($peminjaman->book->cover)
                                <img src="{{ asset('storage/' . $peminjaman->book->cover) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center text-amber-200">
                                    <i class="ri-book-3-line text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Info & Dates -->
                        <div class="flex-grow min-w-0 flex flex-col justify-between py-1">
                            <div>
                                <!-- Title + Badge inline -->
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h4 class="font-black text-gray-800 text-sm md:text-base leading-tight uppercase tracking-tight">{{ $peminjaman->book->judul }}</h4>
                                    @if($peminjaman->status === 'menunggu')
                                        <x-ui.badge color="sky">Menunggu</x-ui.badge>
                                    @elseif($peminjaman->status === 'disetujui')
                                        <x-ui.badge color="amber">Dipinjam</x-ui.badge>
                                    @elseif($peminjaman->status === 'menunggu_kembali')
                                        <x-ui.badge color="indigo">Ditinjau</x-ui.badge>
                                    @elseif($peminjaman->status === 'ditolak')
                                        <x-ui.badge color="red">Ditolak</x-ui.badge>
                                    @else
                                        <x-ui.badge color="emerald" icon="ri-check-line">Selesai</x-ui.badge>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 font-medium mb-0">{{ $peminjaman->book->penulis }}</p>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 pt-2 border-t border-gray-100/60">
                                <div class="space-y-0.5">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Dipinjam</span>
                                    <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                        <i class="ri-calendar-line text-amber-500"></i>
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Target</span>
                                    <span class="text-xs font-semibold {{ $peminjaman->status === 'disetujui' && \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->isPast() ? 'text-rose-600' : 'text-gray-700' }} flex items-center gap-1.5">
                                        <i class="ri-timer-line text-amber-500"></i>
                                        {{ $peminjaman->tanggal_kembali_target ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_target)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Kembali</span>
                                    <span class="text-xs font-semibold text-gray-700 flex items-center gap-1.5">
                                        <i class="ri-checkbox-circle-line text-emerald-500"></i>
                                        {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="space-y-0.5">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block">Denda</span>
                                    <span class="text-xs font-black {{ ($peminjaman->total_denda > 0 || $potentialDenda > 0) ? 'text-rose-600' : 'text-gray-900' }}">
                                        @if($peminjaman->status === 'dikembalikan')
                                            Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}
                                        @elseif($potentialDenda > 0)
                                            Rp {{ number_format($potentialDenda, 0, ',', '.') }}
                                        @else
                                            <span class="text-gray-300 font-normal italic">-</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Area -->
                        <div class="shrink-0 flex items-center justify-end md:justify-center md:w-28 md:border-l md:border-gray-100/60 md:pl-5 pt-3 md:pt-0">
                            @if($peminjaman->status === 'disetujui')
                                <x-ui.button @click="$dispatch('open-modal', { name: 'request-return-{{ $peminjaman->id }}' })" size="sm" variant="outline" class="w-full md:w-auto font-black uppercase text-[9px] tracking-widest">
                                    Kembalikan
                                </x-ui.button>
                                
                                @push('modals')
                                    <x-ui.modal name="request-return-{{ $peminjaman->id }}" title="Pengajuan Pengembalian">
                                        <div class="space-y-6">
                                            <div class="flex flex-col gap-1">
                                                <p class="text-sm text-gray-600">Ingin mengembalikan buku <span class="font-black text-gray-900">"{{ $peminjaman->book->judul }}"</span>?</p>
                                                
                                                @if($potentialDenda > 0)
                                                    <div class="mt-2 p-4 bg-rose-50 border border-rose-100 rounded-xl">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Informasi Denda</span>
                                                                <span class="text-xs text-rose-700 font-bold">Terlambat {{ $hari }} Hari</span>
                                                            </div>
                                                            <div class="flex items-baseline gap-1">
                                                                <span class="text-lg font-black text-rose-600">Rp {{ number_format($potentialDenda, 0, ',', '.') }}</span>
                                                                <span class="text-[10px] text-rose-400 font-bold">({{ abs($hari) }} hari x Rp {{ number_format($globalDenda, 0, ',', '.') }})</span>
                                                            </div>
                                                        </div>
                                                        <p class="mt-2 text-[10px] text-rose-500 leading-tight">Pastikan Anda membayar denda kepada petugas saat mengembalikan buku.</p>
                                                    </div>
                                                @else
                                                    <div class="mt-2 bg-amber-50 p-4 rounded-xl border border-amber-100 text-xs text-amber-800 leading-relaxed">
                                                        Admin akan meninjau kondisi buku sebelum menyetujui pengembalian.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <x-slot name="footer">
                                            <form action="{{ route('siswa.peminjaman.kembalikan', $peminjaman->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <x-ui.button type="submit">Ya, Ajukan</x-ui.button>
                                            </form>
                                            <x-ui.button @click="$dispatch('close-modal', { name: 'request-return-{{ $peminjaman->id }}' })" variant="outline">Nanti</x-ui.button>
                                        </x-slot>
                                    </x-ui.modal>
                                @endpush
                            @endif

                            @if($peminjaman->status === 'menunggu_kembali')
                                <span class="inline-flex items-center gap-1.5 text-indigo-500 text-xs font-bold">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                    </span>
                                    Ditinjau
                                </span>
                            @endif

                            @if($peminjaman->status === 'menunggu')
                                <div class="flex flex-col items-end gap-3">
                                    <div class="flex items-center gap-2 px-2.5 py-1 bg-sky-50 text-sky-600 rounded-full border border-sky-100 shadow-sm animate-pulse">
                                        <i class="ri-loader-4-line animate-spin text-xs"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Diproses</span>
                                    </div>
                                    <x-ui.button @click="$dispatch('open-modal', { name: 'cancel-loan-{{ $peminjaman->id }}' })" 
                                                 variant="outline" size="md" class="!text-rose-500 !border-rose-100 hover:!bg-rose-50 font-bold !text-[12px] !px-3 !py-1">
                                        Batalkan
                                    </x-ui.button>
                                </div>

                                @push('modals')
                                    <x-ui.modal name="cancel-loan-{{ $peminjaman->id }}" title="Konfirmasi Pembatalan">
                                        <div class="space-y-3">
                                            <p class="text-sm text-gray-600">Apakah Anda yakin ingin membatalkan pengajuan peminjaman untuk buku <span class="font-bold text-gray-900">"{{ $peminjaman->book->judul }}"</span>?</p>
                                            <div class="p-3 bg-rose-50 rounded-lg border border-rose-100 text-[11px] text-rose-700 leading-relaxed">
                                                Tindakan ini tidak dapat dibatalkan. Anda harus mengajukan pinjam ulang jika berubah pikiran.
                                            </div>
                                        </div>
                                        <x-slot name="footer">
                                            <form action="{{ route('siswa.peminjaman.batal', $peminjaman->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <x-ui.button type="submit" variant="danger">Ya, Batalkan</x-ui.button>
                                            </form>
                                            <x-ui.button @click="$dispatch('close-modal', { name: 'cancel-loan-{{ $peminjaman->id }}' })" variant="outline">Kembali</x-ui.button>
                                        </x-slot>
                                    </x-ui.modal>
                                @endpush
                            @endif

                            @if($peminjaman->status === 'dikembalikan')
                                <span class="text-emerald-400">
                                    <i class="ri-checkbox-circle-fill text-2xl"></i>
                                </span>
                            @endif
                            
                            @if($peminjaman->status === 'ditolak')
                                <span class="text-rose-400">
                                    <i class="ri-close-circle-fill text-2xl"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <x-ui.empty-state icon="ri-history-line" title="Belum Ada Aktivitas Membaca" subtitle="Katalog buku menantimu untuk dijelajahi. Ayo mulai petualanganmu hari ini!" />
            @endforelse
        </div>

        @if($peminjamans->hasPages())
            <div class="mt-4">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
