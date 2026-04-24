<!-- Desktop Sidebar — Dark Warm -->
<aside class="hidden lg:flex flex-col w-56 bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 h-screen fixed left-0 top-0 z-50 overflow-y-auto border-r border-white/5 shadow-2xl shadow-black/20 transition-all duration-300">
    <!-- Logo -->
    <div class="px-4 py-4 flex items-center gap-2.5 border-b border-white/10">
        <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/20">
            <i class="ri-book-3-line text-white text-sm"></i>
        </div>
        <span class="font-bold text-xl text-white/90 tracking-tight">Perpus<span class="text-amber-400">Kita</span></span>
    </div>

    <!-- Nav Links -->
    <div class="flex-1 px-3 py-3 space-y-1">
        <p class="px-2.5 py-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Utama</p>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all duration-300 {{ request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
            <i class="ri-dashboard-line text-lg {{ request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) ? 'text-amber-400' : '' }}"></i>
            <span>Beranda</span>
        </a>

        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.books.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('admin.books.*') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-book-open-line text-lg {{ request()->routeIs('admin.books.*') ? 'text-amber-400' : '' }}"></i>
                <span>Data Buku</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-bookmark-3-line text-lg {{ request()->routeIs('admin.categories.*') ? 'text-amber-400' : '' }}"></i>
                <span>Kategori</span>
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('admin.peminjaman.*') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <div class="flex items-center gap-2.5">
                    <i class="ri-exchange-line text-lg {{ request()->routeIs('admin.peminjaman.*') ? 'text-amber-400' : '' }}"></i>
                    <span>Transaksi</span>
                </div>
                @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-lg shadow-rose-500/20 animate-pulse">
                        {{ $pendingRequestsCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('admin.anggota.*') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-group-line text-lg {{ request()->routeIs('admin.anggota.*') ? 'text-amber-400' : '' }}"></i>
                <span>Anggota</span>
            </a>
            <div class="pt-2"></div>
            <p class="px-2.5 py-2 text-[10px] font-black text-gray-500 uppercase tracking-widest">Laporan</p>
            <a href="{{ route('admin.laporan.denda') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('admin.laporan.denda') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-refund-2-line text-lg {{ request()->routeIs('admin.laporan.denda') ? 'text-amber-400' : '' }}"></i>
                <span>Laporan Denda</span>
            </a>
        @else
            <a href="{{ route('siswa.books') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('siswa.books') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-search-line text-lg {{ request()->routeIs('siswa.books') ? 'text-amber-400' : '' }}"></i>
                <span>Katalog Buku</span>
            </a>
            <a href="{{ route('siswa.peminjaman.riwayat') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm transition-all {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'bg-amber-500/15 text-amber-400 font-bold' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <div class="flex items-center gap-2.5">
                    <i class="ri-history-line text-lg {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'text-amber-400' : '' }}"></i>
                    <span>Riwayat</span>
                </div>
                @if(isset($activeLoansCount) && $activeLoansCount > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white shadow-lg shadow-amber-500/20">
                        {{ $activeLoansCount }}
                    </span>
                @endif
            </a>
        @endif
    </div>

    <!-- Profile -->
    <div class="px-3 py-3 border-t border-white/10">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl hover:bg-white/5 transition-all group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xs font-bold shadow-lg shadow-amber-500/10">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-bold text-gray-200 truncate leading-none mb-1">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="mt-1 w-full flex items-center gap-2.5 px-2.5 py-2 rounded-xl text-gray-500 hover:text-rose-500 hover:bg-rose-500/5 transition-all text-xs font-bold uppercase tracking-widest">
                <i class="ri-logout-box-r-line text-base"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Navigation — Integrated Header (Fixed Top) -->
<nav x-data="{ open: false }" class="lg:hidden bg-white fixed top-0 left-0 right-0 z-40 border-b border-amber-100/50 shadow-sm">
    <div class="px-4 flex items-center justify-between h-14 gap-2">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
            <div class="w-7 h-7 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/20">
                <i class="ri-book-3-line text-white text-xs"></i>
            </div>
            <span class="font-black text-xl text-gray-800 tracking-tight">Perpus<span class="text-amber-500">Kita</span></span>
        </a>

        <!-- Dynamic Title for Mobile Guidance -->
        @if(!request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) && isset($header))
            <div class="flex-1 flex justify-center overflow-hidden">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate text-center">{{ $header }}</span>
            </div>
        @endif

        <button @click="open = !open" class="p-2 rounded-xl text-gray-600 hover:bg-amber-50 transition-all active:scale-95 shrink-0">
            <i :class="open ? 'ri-close-line' : 'ri-menu-line'" class="text-xl"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-cloak @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="border-t border-amber-50 bg-white shadow-xl">
        <div class="py-3 px-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm {{ request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                <i class="ri-dashboard-line text-lg"></i> Beranda
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm {{ request()->routeIs('admin.books.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <i class="ri-book-open-line text-lg"></i> Data Buku
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <i class="ri-bookmark-3-line text-lg"></i> Kategori
                </a>
                <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm {{ request()->routeIs('admin.peminjaman.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-exchange-line text-lg"></i> Transaksi
                    </div>
                    @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-lg shadow-rose-500/20">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm {{ request()->routeIs('admin.anggota.*') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <i class="ri-group-line text-lg"></i> Anggota
                </a>
            @else
                <a href="{{ route('siswa.books') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm {{ request()->routeIs('siswa.books') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <i class="ri-search-line text-lg"></i> Katalog Buku
                </a>
                <a href="{{ route('siswa.peminjaman.riwayat') }}" class="flex items-center justify-between px-3 py-2 rounded-xl text-sm {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'bg-amber-500 text-white font-bold' : 'text-gray-600' }}">
                    <div class="flex items-center gap-3">
                        <i class="ri-history-line text-lg"></i> Riwayat
                    </div>
                    @if(isset($activeLoansCount) && $activeLoansCount > 0)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white shadow-lg shadow-amber-500/20">
                            {{ $activeLoansCount }}
                        </span>
                    @endif
                </a>
            @endif
        </div>

        <div class="py-3 px-4 border-t border-amber-50 flex items-center justify-between">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xs font-bold shadow-lg shadow-amber-500/10 group-hover:scale-105 transition-transform">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-800 group-hover:text-amber-600 transition-colors">{{ explode(' ', Auth::user()->name)[0] }}</p>
                    <p class="text-[10px] text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                    <i class="ri-logout-box-r-line text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</nav>
