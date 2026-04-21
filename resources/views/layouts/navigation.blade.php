<!-- Desktop Sidebar — Dark Warm -->
<aside class="hidden lg:flex flex-col w-56 bg-gradient-to-b from-gray-900 via-gray-900 to-gray-950 h-screen fixed left-0 top-0 z-50 overflow-y-auto">
    <!-- Logo -->
    <div class="px-4 py-4 flex items-center gap-2.5 border-b border-white/10">
        <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg shadow-amber-500/20">
            <i class="ri-book-3-line text-white text-sm"></i>
        </div>
        <span class="font-semibold text-sm text-white/90 lowercase tracking-tight">perpus<span class="text-amber-400">kita</span></span>
    </div>

    <!-- Nav Links -->
    <div class="flex-1 px-3 py-3 space-y-0.5">
        <p class="px-2.5 py-2 text-[10px] font-medium text-gray-500 uppercase tracking-wider">Menu</p>

        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
            <i class="ri-dashboard-line text-base {{ request()->routeIs(['dashboard', 'admin.dashboard', 'siswa.dashboard']) ? 'text-amber-400' : '' }}"></i>
            <span>Beranda</span>
        </a>

        @if (Auth::user()->role === 'admin')
            <a href="{{ route('admin.books.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.books.*') ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-book-open-line text-base {{ request()->routeIs('admin.books.*') ? 'text-amber-400' : '' }}"></i>
                <span>Data Buku</span>
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.peminjaman.*') ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-exchange-line text-base {{ request()->routeIs('admin.peminjaman.*') ? 'text-amber-400' : '' }}"></i>
                <span>Transaksi</span>
            </a>
            <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.anggota.*') ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-group-line text-base {{ request()->routeIs('admin.anggota.*') ? 'text-amber-400' : '' }}"></i>
                <span>Anggota</span>
            </a>
        @else
            <a href="{{ route('siswa.books') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.books') ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-search-line text-base {{ request()->routeIs('siswa.books') ? 'text-amber-400' : '' }}"></i>
                <span>Katalog Buku</span>
            </a>
            <a href="{{ route('siswa.peminjaman.riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'bg-amber-500/15 text-amber-400 font-medium' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                <i class="ri-history-line text-base {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'text-amber-400' : '' }}"></i>
                <span>Riwayat</span>
            </a>
        @endif
    </div>

    <!-- Profile -->
    <div class="px-3 py-3 border-t border-white/10">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg hover:bg-white/5 transition-all">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xs font-semibold shadow-sm">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-medium text-gray-200 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-gray-500 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-sm">
                <i class="ri-logout-box-r-line text-base"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

<!-- Mobile Navigation -->
<nav x-data="{ open: false }" class="lg:hidden bg-white/90 backdrop-blur-sm border-b border-amber-100 sticky top-0 z-40">
    <div class="px-4 flex justify-between h-14 items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-sm">
                <i class="ri-book-3-line text-white text-xs"></i>
            </div>
            <span class="font-semibold text-sm text-gray-800 lowercase tracking-tight">perpus<span class="text-amber-600">kita</span></span>
        </a>

        <button @click="open = !open" class="p-2 rounded-lg text-gray-600 hover:bg-amber-50 transition-colors">
            <i :class="open ? 'ri-close-line' : 'ri-menu-line'" class="text-lg"></i>
        </button>
    </div>

    <div :class="{'block': open, 'hidden': !open}" class="hidden border-t border-amber-100 bg-white">
        <div class="py-2 px-3 space-y-0.5">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                <i class="ri-dashboard-line text-base"></i> Beranda
            </a>

            @if (Auth::user()->role === 'admin')
                <a href="{{ route('admin.books.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('admin.books.*') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                    <i class="ri-book-open-line text-base"></i> Data Buku
                </a>
                <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('admin.peminjaman.*') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                    <i class="ri-exchange-line text-base"></i> Transaksi
                </a>
                <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('admin.anggota.*') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                    <i class="ri-group-line text-base"></i> Anggota
                </a>
            @else
                <a href="{{ route('siswa.books') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('siswa.books') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                    <i class="ri-search-line text-base"></i> Katalog Buku
                </a>
                <a href="{{ route('siswa.peminjaman.riwayat') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-sm {{ request()->routeIs('siswa.peminjaman.riwayat') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-gray-600' }}">
                    <i class="ri-history-line text-base"></i> Riwayat
                </a>
            @endif
        </div>

        <div class="py-2 px-3 border-t border-amber-100">
            <div class="px-2.5 py-2 flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center text-xs font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="event.preventDefault(); this.closest('form').submit();" class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-rose-600 hover:bg-rose-50 text-sm transition-colors">
                    <i class="ri-logout-box-r-line text-base"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>
