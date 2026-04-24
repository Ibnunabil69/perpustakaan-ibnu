<x-app-layout>
    <x-slot name="header">Beranda Siswa</x-slot>

    @php
        $getContrastColor = function($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) != 6) return 'text-white';
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
            return ($yiq >= 150) ? 'text-slate-900' : 'text-white';
        };
    @endphp

    <div class="space-y-6 sm:space-y-8 pb-8">
        <!-- Hero Section: Discovery -->
        <!-- Compact Gray Hero Section -->
        <section class="relative overflow-hidden rounded-2xl bg-gray-900 text-white px-6 py-10 md:px-10 md:py-12 flex flex-col items-center text-center shadow-lg border border-gray-800">
            <!-- Subtle Texture -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl"></div>
            
            <h1 class="relative z-10 text-xl md:text-3xl font-bold text-white mb-2">
                Mau Baca <span class="text-amber-400">Apa Hari Ini?</span>
            </h1>
            <p class="relative z-10 text-xs md:text-sm text-gray-200 max-w-lg mb-8 leading-relaxed">
               Hai<span class="font-semibold"> {{ explode(' ', Auth::user()->name)[0] }}</span>, temukan koleksi buku berkualitas untuk menemanimu belajar.
            </p>

            <!-- Clean Dark Search Bar -->
            <form action="{{ route('siswa.books') }}" method="GET" class="relative z-10 w-full max-w-xl relative group px-2 sm:px-0 text-left">
                <input type="text" name="search" placeholder="Cari judul buku atau penulis..." 
                    class="w-full h-11 md:h-12 pl-12 pr-4 bg-white/10 border border-white/20 rounded-xl focus:bg-white focus:text-gray-900 focus:ring-4 focus:ring-amber-500/20 transition-all outline-none text-sm text-white placeholder-white/40">
                <i class="ri-search-2-line absolute left-6 md:left-4 top-1/2 -translate-y-1/2 text-lg text-amber-400 group-focus-within:text-amber-500 transition-colors"></i>
                <button type="submit" class="hidden md:block absolute right-2 top-1/2 -translate-y-1/2 px-5 py-1.5 bg-amber-500 text-white text-[10px] font-bold rounded-lg hover:bg-amber-600 transition-colors">
                    Cari
                </button>
            </form>
        </section>

        <!-- Stats Section: Overview (1 Column on Mobile for spacing) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500">
                    <i class="ri-book-read-line text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dipinjam</p>
                    <p class="text-lg font-black text-gray-900">{{ $activeLoansCount }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500">
                    <i class="ri-history-line text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Selesai</p>
                    <p class="text-lg font-black text-gray-900">{{ $totalLoansCount }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500">
                    <i class="ri-alarm-warning-line text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terlambat</p>
                    <p class="text-lg font-black text-gray-900">{{ $overdueLoansCount }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                    <i class="ri-wallet-3-line text-xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Denda</p>
                    <p class="text-lg font-black text-gray-900">Rp{{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Latest Books Section -->
        <div class="space-y-5 pt-2">
            <div class="flex items-end justify-between border-b border-gray-100 pb-3 px-1">
                <div class="space-y-0.5">
                    <h2 class="text-base md:text-lg font-bold text-gray-900 tracking-tight">Koleksi Terbaru</h2>
                    <p class="text-[10px] md:text-sm text-gray-400 leading-none">Koleksi buku terbaru di perpustakaan.</p>
                </div>
                <a href="{{ route('siswa.books') }}" class="group text-[10px] md:text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-all">
                    Lihat Semua <i class="ri-arrow-right-line group-hover:translate-x-0.5 transition-transform"></i>
                </a>
            </div>

            <!-- Books Horizontal Scroll on Mobile / Grid on Desktop -->
            <style>
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
            <div class="flex md:grid overflow-x-auto md:overflow-visible snap-x snap-mandatory gap-3 md:gap-6 pb-4 md:pb-0 md:grid-cols-4 -mx-4 px-4 md:mx-0 md:px-0 no-scrollbar">
                @foreach ($teranyar as $book)
                    <div class="flex-none w-52 md:w-auto snap-start group bg-white rounded-xl md:rounded-2xl border border-gray-100 p-2 md:p-3 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 flex flex-col">
                        <!-- Cover Wrapper -->
                        <div class="aspect-[5/7] rounded-lg md:rounded-xl overflow-hidden bg-gray-50 border border-gray-100 relative mb-2 md:mb-3">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                                    <i class="ri-book-3-line text-3xl text-amber-200"></i>
                                </div>
                            @endif
                            
                            <!-- Floating Indicators -->
                            <div class="absolute top-2 left-2 flex flex-wrap gap-1 pointer-events-none z-10">
                                @if($book->created_at->diffInDays(now()) < 7)
                                    <span class="px-2 py-0.5 bg-emerald-500 text-white rounded-md text-[8px] font-bold uppercase tracking-wider shadow-sm flex items-center gap-1">
                                        <i class="ri-sparkling-fill text-[9px]"></i>
                                        Baru
                                    </span>
                                @endif
                                
                                <span class="px-2 py-0.5 text-white rounded-md text-[8px] font-bold uppercase tracking-wider shadow-sm" 
                                      style="background-color: {{ $book->category->color ?? '#64748b' }}">
                                    {{ $book->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-2 md:mt-3 px-1 flex-1 flex flex-col">
                            <h3 class="text-[11px] md:text-xs font-bold text-gray-800 line-clamp-2 md:line-clamp-1 mb-1 group-hover:text-amber-600 transition-colors leading-tight tracking-tight" title="{{ $book->judul }}">{{ $book->judul }}</h3>
                            
                            <div class="flex flex-col gap-0.5 mb-1">
                                <p class="text-[10px] text-gray-400 font-medium truncate italic" title="{{ $book->penulis }}">{{ $book->penulis }}</p>
                                @if($book->stok > 0)
                                    <div class="flex items-center gap-1 text-emerald-600">
                                        <i class="ri-checkbox-circle-fill text-[9px]"></i>
                                        <span class="text-[9px] font-bold">Stok: {{ $book->stok }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1 text-rose-500">
                                        <i class="ri-close-circle-fill text-[9px]"></i>
                                        <span class="text-[9px] font-bold uppercase text-[8px]">Habis</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Action Button -->
                            <div class="mt-auto pt-2 border-t border-gray-50">
                                <a href="{{ route('siswa.books.pinjam.form', $book->id) }}" 
                                   class="block w-full py-1.5 md:py-2 bg-amber-500 hover:bg-amber-600 text-white text-[8px] md:text-[9px] font-black rounded-lg text-center transition-all shadow-sm active:scale-95 uppercase tracking-widest">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>