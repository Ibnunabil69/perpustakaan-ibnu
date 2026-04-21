<x-app-layout>
    <x-slot name="header">Beranda</x-slot>

    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500 rounded-xl p-5 text-white relative overflow-hidden">
            <div class="absolute -top-10 right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h2 class="text-base font-semibold">Hai, {{ Auth::user()->name }} 👋</h2>
                <p class="text-sm text-white/80 mt-1">Ringkasan aktivitas peminjaman Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.card class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <i class="ri-book-open-line text-base"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Buku Dipinjam</p>
                    <p class="text-xl font-bold text-gray-800">{{ $bukuDipinjam }}</p>
                </div>
            </x-ui.card>

            <a href="{{ route('siswa.peminjaman.riwayat') }}" class="block group">
                <x-ui.card class="p-4 flex items-center gap-3 group-hover:shadow-md transition-all h-full">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 text-gray-400 group-hover:bg-gradient-to-br group-hover:from-teal-100 group-hover:to-teal-200 group-hover:text-teal-600 flex items-center justify-center flex-shrink-0 transition-all">
                        <i class="ri-history-line text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500">Akses Cepat</p>
                        <p class="text-sm font-medium text-amber-600 mt-0.5">Lihat Riwayat Peminjaman →</p>
                    </div>
                </x-ui.card>
            </a>
        </div>
    </div>
</x-app-layout>
