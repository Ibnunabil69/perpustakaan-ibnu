<x-app-layout>
    <x-slot name="header">Katalog Buku</x-slot>

    <div class="space-y-6">
        <!-- Toolbar -->
        <div class="space-y-3">
            <!-- Search Bar — Full Width -->
            <form action="{{ route('siswa.books') }}" method="GET" id="catalogFilterForm">
                @if(request('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="ri-search-2-line text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul, penulis, atau penerbit..."
                            class="block w-full pl-10 pr-3 py-2.5 bg-white border border-amber-200/80 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20 transition-all shadow-sm">
                    </div>
                    <button type="submit" class="shrink-0 h-[42px] px-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-sm font-bold shadow-sm shadow-amber-200/50 transition-all active:scale-95 flex items-center gap-1.5">
                        <i class="ri-search-line"></i>
                        <span class="hidden sm:inline">Cari</span>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('siswa.books', request()->except('search', 'page')) }}" class="shrink-0 h-[42px] w-[42px] flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-rose-500 hover:border-rose-200 transition-all">
                            <i class="ri-close-line text-lg"></i>
                        </a>
                    @endif
                </div>
            </form>

            <!-- Category Chips — Horizontal Scroll -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide -mx-1 px-1">
                <a href="{{ route('siswa.books', request()->except('category_id', 'page')) }}"
                    class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all duration-200 {{ !request('category_id') ? 'bg-amber-500 text-white shadow-sm shadow-amber-500/20' : 'bg-white text-gray-600 border border-gray-200 hover:border-amber-300 hover:text-amber-600' }}">
                    <i class="ri-apps-line text-sm"></i> Semua
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('siswa.books', array_merge(request()->except('page'), ['category_id' => $cat->id])) }}"
                        class="shrink-0 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold transition-all duration-200 whitespace-nowrap {{ request('category_id') == $cat->id ? 'text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-amber-300 hover:text-amber-600' }}"
                        @if(request('category_id') == $cat->id) style="background-color: {{ $cat->color ?? '#f59e0b' }}; border-color: {{ $cat->color ?? '#f59e0b' }};" @endif>
                        @if(request('category_id') == $cat->id)
                            <i class="ri-check-line text-sm"></i>
                        @else
                            <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $cat->color ?? '#64748b' }}"></span>
                        @endif
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Active Filter Summary -->
            @if(request('search') || request('category_id'))
                <div class="flex items-center justify-between bg-amber-50/80 rounded-lg px-3 py-2 border border-amber-100">
                    <div class="flex items-center gap-2 flex-wrap text-xs text-amber-800">
                        <i class="ri-filter-3-line text-amber-500"></i>
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-md border border-amber-200 font-medium">
                                "{{ request('search') }}"
                                <a href="{{ route('siswa.books', request()->except('search', 'page')) }}" class="text-gray-400 hover:text-rose-500 ml-0.5"><i class="ri-close-line text-xs"></i></a>
                            </span>
                        @endif
                        @if(request('category_id'))
                            @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
                            @if($activeCat)
                                <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-md border border-amber-200 font-medium">
                                    {{ $activeCat->name }}
                                    <a href="{{ route('siswa.books', request()->except('category_id', 'page')) }}" class="text-gray-400 hover:text-rose-500 ml-0.5"><i class="ri-close-line text-xs"></i></a>
                                </span>
                            @endif
                        @endif
                    </div>
                    <a href="{{ route('siswa.books') }}" class="text-[10px] font-bold text-rose-600 hover:text-rose-700 uppercase tracking-wider shrink-0 ml-2">
                        Reset
                    </a>
                </div>
            @endif
        </div>

        @if($activeLoansCount >= 3)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-4 animate-pulse">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                    <i class="ri-error-warning-fill text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">Batas Peminjaman Tercapai</h4>
                    <p class="text-xs text-amber-700 leading-relaxed">Anda sedang meminjam/mengajukan 3 buku. Silakan
                        kembalikan buku terlebih dahulu untuk meminjam yang baru.</p>
                </div>
            </div>
        @endif

        <!-- Book Grid (Optimized for 2 Columns on Mobile) -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 pb-12">
            @forelse ($books as $book)
                <div class="group relative bg-white rounded-xl md:rounded-2xl border border-gray-100 p-2 md:p-3 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300 flex flex-col">
                    <!-- Cover Container -->
                    <div class="aspect-[5/7] rounded-lg md:rounded-xl overflow-hidden bg-gray-50 relative border border-gray-100 mb-2 md:mb-3">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                                <i class="ri-book-3-line text-4xl text-amber-200"></i>
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

                    <!-- Content -->
                    <div class="flex-1 flex flex-col">
                        <h3 class="text-[11px] md:text-xs font-bold text-gray-800 line-clamp-2 md:line-clamp-1 mb-1 group-hover:text-amber-600 transition-colors leading-tight tracking-tight" title="{{ $book->judul }}">
                            {{ $book->judul }}
                        </h3>
                        
                        <div class="flex flex-col gap-1 mb-1">
                            <p class="text-[10px] text-gray-400 font-medium truncate italic" title="{{ $book->penulis }}">{{ $book->penulis }}</p>
                            <div class="flex items-center justify-between gap-1">
                                @if($book->stok > 0)
                                    <div class="flex items-center gap-1 text-emerald-600">
                                        <i class="ri-checkbox-circle-fill text-[9px]"></i>
                                        <span class="text-[9px] font-bold">Stok: {{ $book->stok }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-1 text-rose-500">
                                        <i class="ri-close-circle-fill text-[9px]"></i>
                                        <span class="text-[9px] font-bold uppercase">Habis</span>
                                    </div>
                                @endif
                            </div>
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
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mb-4">
                        <i class="ri-book-3-line text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Buku tidak ditemukan</h3>
                    <p class="text-sm text-gray-400 max-w-xs mx-auto">Kami tidak dapat menemukan buku dengan kriteria
                        pencarian tersebut.</p>
                    <a href="{{ route('siswa.books') }}"
                        class="mt-4 text-amber-600 font-bold text-sm border-b-2 border-amber-600 pb-0.5">Lihat semua
                        koleksi</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>
</x-app-layout>