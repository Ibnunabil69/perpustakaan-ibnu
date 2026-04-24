<x-app-layout>
    <x-slot name="header">Dashboard Admin</x-slot>

    <div class="space-y-6">
        <!-- Hero Section — Vibrant Gradient -->
        <div
            class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-3xl p-6 sm:p-8 shadow-lg shadow-gray-500/20 relative overflow-hidden text-white flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Decorative blobs -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-20 -mt-20 blur-3xl pointer-events-none">
            </div>
            <div class="absolute bottom-0 left-1/4 w-40 h-40 bg-white/20 rounded-full blur-2xl pointer-events-none">
            </div>

            <div class="relative z-10">
                <p class="text-amber-300 text-xs font-bold tracking-wider uppercase mb-1.5">
                    {{ now()->translatedFormat('l, d F Y') }}</p>
                <h2 class="text-2xl sm:text-3xl font-black mb-1.5">Selamat Datang,
                    {{ explode(' ', Auth::user()->name)[0] }} 👋</h2>
                <p class="text-amber-50 text-sm sm:text-base opacity-90">Mari kelola perpustakaan dengan lebih mudah
                    hari ini.</p>
            </div>

            <div class="relative z-10 flex flex-wrap sm:flex-nowrap gap-3">
                <a href="{{ route('admin.peminjaman.index') }}"
                    class="flex-1 sm:flex-none text-center px-5 py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-bold rounded-xl text-sm hover:scale-105 transition-all shadow-sm flex justify-center items-center gap-2 backdrop-blur-md">
                    <i class="ri-exchange-box-line text-lg text-amber-400"></i> Transaksi
                </a>
                <a href="{{ route('admin.books.create') }}"
                    class="flex-1 sm:flex-none text-center px-5 py-3 bg-amber-500 text-white font-bold rounded-xl text-sm hover:bg-amber-600 hover:scale-105 transition-all shadow-lg shadow-amber-500/20 flex justify-center items-center gap-2">
                    <i class="ri-add-circle-line text-lg"></i> Buku Baru
                </a>
            </div>
        </div>

        <!-- Smart Alerts Row (Only shows if there's action needed) -->
        @if($pendingCount > 0 || $terlambat > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if($pendingCount > 0)
                    <div
                        class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3.5 overflow-hidden">
                            <div
                                class="w-10 h-10 shrink-0 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                <i class="ri-time-line text-xl"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-sm font-bold text-amber-900 truncate">{{ $pendingCount }} Permintaan Baru</h4>
                                <p class="text-[11px] font-medium text-amber-700/80 truncate">Menunggu persetujuan Anda</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.peminjaman.index', ['status' => 'menunggu']) }}"
                            class="shrink-0 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition-colors">Tinjau</a>
                    </div>
                @endif

                @if($terlambat > 0)
                    <div
                        class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3.5 overflow-hidden">
                            <div
                                class="w-10 h-10 shrink-0 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                <i class="ri-alarm-warning-line text-xl"></i>
                            </div>
                            <div class="truncate">
                                <h4 class="text-sm font-bold text-rose-900 truncate">{{ $terlambat }} Peminjaman Telat</h4>
                                <p class="text-[11px] font-medium text-rose-700/80 truncate">Melewati batas pengembalian</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}"
                            class="shrink-0 px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-colors">Lihat</a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Core Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Peminjaman Aktif -->
            <x-ui.card
                class="p-5 flex flex-col justify-between group hover:border-blue-200 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="ri-hand-coin-line"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100">Aktif</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-800">{{ $peminjamanAktif }}</h3>
                    <p class="text-[11px] font-semibold text-gray-400 mt-0.5">Buku Dipinjam</p>
                </div>
            </x-ui.card>

            <!-- Total Pendapatan Denda -->
            <x-ui.card
                class="p-5 flex flex-col justify-between group hover:border-emerald-200 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <span
                        class="text-[10px] font-bold px-2 py-1 bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">Total</span>
                </div>
                <div>
                    <h3 class="text-xl sm:text-2xl font-black text-gray-800 truncate">
                        Rp{{ number_format($totalDendaCollected, 0, ',', '.') }}</h3>
                    <p class="text-[11px] font-semibold text-gray-400 mt-0.5">Pendapatan Denda</p>
                </div>
            </x-ui.card>

            <!-- Total Anggota -->
            <x-ui.card
                class="p-5 flex flex-col justify-between group hover:border-teal-200 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="ri-group-line"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-800">{{ $totalSiswa }}</h3>
                    <p class="text-[11px] font-semibold text-gray-400 mt-0.5">Total Anggota</p>
                </div>
            </x-ui.card>

            <!-- Total Buku -->
            <x-ui.card
                class="p-5 flex flex-col justify-between group hover:border-amber-200 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="ri-book-3-line"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-gray-800">{{ $totalBuku }}</h3>
                    <p class="text-[11px] font-semibold text-gray-400 mt-0.5">Koleksi Buku</p>
                </div>
            </x-ui.card>
        </div>

        <!-- Statistics Section — Visual Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Line Chart: Borrowing Trends -->
            <x-ui.card class="p-5 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Tren Peminjaman</h3>
                        <p class="text-[10px] font-medium text-gray-400 mt-0.5">7 hari terakhir</p>
                    </div>
                    <div
                        class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold border border-amber-100">
                        Visual Update
                    </div>
                </div>
                <div class="h-[250px] w-full">
                    <canvas id="peminjamanChart"></canvas>
                </div>
            </x-ui.card>

            <!-- Doughnut Chart: Categories -->
            <x-ui.card class="p-5 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Distribusi Kategori</h3>
                        <p class="text-[10px] font-medium text-gray-400 mt-0.5">Berdasarkan koleksi buku</p>
                    </div>
                </div>
                <div class="h-[250px] w-full flex items-center justify-center">
                    <canvas id="categoryChart"></canvas>
                </div>
            </x-ui.card>
        </div>

        <!-- Content Grid: Transactions & Popular Books -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions (2 Columns) -->
            <x-ui.card class="lg:col-span-2 flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Transaksi Terbaru</h3>
                        <p class="text-[10px] font-medium text-gray-400 mt-0.5">Aktivitas peminjaman terkini</p>
                    </div>
                    <a href="{{ route('admin.peminjaman.index') }}"
                        class="text-xs font-bold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors">
                        Lihat Semua
                    </a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/50 text-[10px] uppercase tracking-wider font-bold text-gray-400">
                            <tr>
                                <th class="px-5 py-3 rounded-tl-xl">Anggota & Buku</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3 rounded-tr-xl">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($recentPeminjaman as $p)
                                <tr class="hover:bg-amber-50/20 transition-colors group">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0 text-gray-500 font-bold text-xs group-hover:bg-amber-100 group-hover:text-amber-600 transition-colors">
                                                {{ substr($p->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 text-xs">{{ $p->user->name }}</div>
                                                <div
                                                    class="text-[11px] font-medium text-gray-500 truncate max-w-[150px] sm:max-w-xs">
                                                    {{ $p->book->judul }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="text-xs font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</div>
                                        <div class="text-[10px] font-medium text-gray-400">Target:
                                            {{ \Carbon\Carbon::parse($p->tanggal_kembali_target)->format('d M') }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @if($p->status === 'menunggu')
                                            <x-ui.badge color="amber">Menunggu</x-ui.badge>
                                        @elseif($p->status === 'disetujui')
                                            <x-ui.badge color="blue">Dipinjam</x-ui.badge>
                                        @elseif($p->status === 'menunggu_kembali')
                                            <x-ui.badge color="indigo">Ditinjau</x-ui.badge>
                                        @elseif($p->status === 'ditolak')
                                            <x-ui.badge color="rose">Ditolak</x-ui.badge>
                                        @else
                                            <x-ui.badge color="emerald">Selesai</x-ui.badge>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-8 text-center text-sm text-gray-500">Belum ada transaksi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            <!-- Popular Books (1 Column) -->
            <x-ui.card class="flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800">Buku Terpopuler</h3>
                        <p class="text-[10px] font-medium text-gray-400 mt-0.5">Berdasarkan jumlah peminjaman</p>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="ri-fire-fill text-lg"></i>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    @forelse ($popularBooks as $index => $book)
                        <div class="flex items-center gap-3 group">
                            <!-- Rank Number -->
                            <div
                                class="w-5 text-center font-black {{ $index < 3 ? 'text-amber-500' : 'text-gray-300' }} text-lg">
                                {{ $index + 1 }}
                            </div>
                            <!-- Cover -->
                            <div
                                class="w-10 h-14 rounded-lg bg-gray-50 border border-gray-200 overflow-hidden shadow-sm flex-shrink-0 flex items-center justify-center relative">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <i class="ri-book-3-line text-gray-300"></i>
                                @endif
                            </div>
                            <!-- Info -->
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-xs font-bold text-gray-800 truncate group-hover:text-amber-600 transition-colors">
                                    {{ $book->judul }}</p>
                                <p class="text-[10px] font-medium text-gray-400 truncate">{{ $book->penulis }}</p>
                            </div>
                            <!-- Stats -->
                            <div class="text-right shrink-0">
                                <span class="block text-xs font-black text-gray-700">{{ $book->peminjaman_count ?? 0 }}<span
                                        class="text-[9px] font-semibold text-gray-400 ml-0.5">kali</span></span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-gray-500 py-4">Belum ada data peminjaman.</div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Peminjaman Chart
            const ctxPeminjaman = document.getElementById('peminjamanChart').getContext('2d');
            new Chart(ctxPeminjaman, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Peminjaman',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#f59e0b',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } },
                            grid: { borderDash: [5, 5], color: '#f1f5f9' }
                        },
                        x: {
                            ticks: { color: '#94a3b8', font: { size: 10 } },
                            grid: { display: false }
                        }
                    }
                }
            });

            // Category Chart
            const ctxCategory = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($catLabels) !!},
                    datasets: [{
                        data: {!! json_encode($catData) !!},
                        backgroundColor: [
                            '#f59e0b', '#3b82f6', '#10b981', '#f43f5e', '#8b5cf6', '#06b6d4', '#eab308'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: { size: 11, weight: '600' },
                                color: '#475569'
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        </script>
    @endpush
</x-app-layout>