<x-app-layout>
    <x-slot name="header">Beranda</x-slot>

    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 rounded-xl p-5 text-white relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-amber-500/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-1/3 w-24 h-24 bg-teal-500/10 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <h2 class="text-base font-semibold">Selamat Datang, {{ Auth::user()->name }} 👋</h2>
                <p class="text-sm text-gray-400 mt-1">Berikut ringkasan aktivitas perpustakaan saat ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-ui.card class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <i class="ri-book-3-line text-base"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Buku</p>
                    <p class="text-xl font-bold text-gray-800">{{ $totalBuku }}</p>
                </div>
            </x-ui.card>

            <x-ui.card class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-100 to-emerald-200 text-teal-600 flex items-center justify-center flex-shrink-0">
                    <i class="ri-exchange-line text-base"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Peminjaman Aktif</p>
                    <p class="text-xl font-bold text-gray-800">{{ $totalPeminjamanAktif }}</p>
                </div>
            </x-ui.card>

            <x-ui.card class="p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <i class="ri-group-line text-base"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Total Siswa</p>
                    <p class="text-xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>
