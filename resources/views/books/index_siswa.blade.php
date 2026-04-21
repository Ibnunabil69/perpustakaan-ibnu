<x-app-layout>
    <x-slot name="header">Katalog Buku</x-slot>

    @php
        $coverColors = [
            'from-amber-200 to-orange-200',
            'from-teal-200 to-emerald-200',
            'from-rose-200 to-pink-200',
            'from-sky-200 to-blue-200',
            'from-violet-200 to-purple-200',
            'from-lime-200 to-green-200',
        ];
    @endphp

    <div class="space-y-4">
        <!-- Search -->
        <form action="{{ route('siswa.books') }}" method="GET" class="max-w-md">
            <x-ui.input type="text" name="search" :value="request('search')" placeholder="Cari judul, penulis, atau penerbit..." icon="ri-search-2-line" />
        </form>

        @if(request('search'))
            <p class="text-sm text-gray-500">
                Hasil untuk: <span class="font-medium text-gray-800">"{{ request('search') }}"</span>
                <a href="{{ route('siswa.books') }}" class="text-amber-600 hover:text-amber-700 ml-1">Bersihkan</a>
            </p>
        @endif

        <!-- Book Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse ($books as $book)
                @php $color = $coverColors[$loop->index % count($coverColors)]; @endphp
                <x-ui.card class="flex flex-col group">
                    <!-- Cover area — colorful gradient per-book -->
                    <div class="h-28 bg-gradient-to-br {{ $color }} flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <i class="ri-book-3-line text-2xl text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
                    </div>
                    
                    <div class="p-3.5 flex-grow">
                        <h4 class="font-medium text-sm text-gray-800 leading-tight mb-1 line-clamp-2 group-hover:text-amber-700 transition-colors">{{ $book->judul }}</h4>
                        <p class="text-xs text-gray-500">{{ $book->penulis }}</p>
                        <div class="mt-2 flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $book->penerbit }}</span>
                            <span>{{ $book->tahun_terbit }}</span>
                        </div>
                    </div>
                    <div class="px-3.5 py-2.5 border-t border-amber-50 flex items-center justify-between">
                        @if($book->stok > 0)
                            <x-ui.badge color="emerald">Tersedia ({{ $book->stok }})</x-ui.badge>
                            <form action="{{ route('siswa.books.pinjam', $book->id) }}" method="POST">
                                @csrf
                                <x-ui.button type="submit" size="sm">Pinjam</x-ui.button>
                            </form>
                        @else
                            <x-ui.badge color="rose">Habis</x-ui.badge>
                        @endif
                    </div>
                </x-ui.card>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center mx-auto mb-3">
                        <i class="ri-search-line text-xl text-amber-400"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-600">Buku tidak ditemukan</p>
                    <a href="{{ route('siswa.books') }}" class="text-sm text-amber-600 hover:text-amber-700 mt-1 inline-block">Lihat semua</a>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $books->links() }}
        </div>
    </div>
</x-app-layout>
