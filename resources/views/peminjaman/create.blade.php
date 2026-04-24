<x-app-layout>
    <x-slot name="header">Pinjam Buku</x-slot>

    <div class="max-w-3xl mx-auto pb-10 px-4">
        <!-- Breadcrumb / Back Navigation -->
        <div class="mb-5">
            <a href="{{ route('siswa.books') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-amber-600 transition-all group">
                <div class="w-7 h-7 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center group-hover:border-amber-200 transition-colors">
                    <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i>
                </div>
                Kembali ke Katalog
            </a>
        </div>

        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Side: Book Cover (More Compact) -->
            <div class="w-full md:w-56 shrink-0 space-y-4">
                <div class="bg-white p-2.5 rounded-2xl shadow-sm border border-gray-100 group">
                    <div class="aspect-[5/7] rounded-xl overflow-hidden bg-gray-50 border border-gray-100 relative shadow-inner">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center">
                                <i class="ri-book-3-line text-5xl text-amber-200/60"></i>
                            </div>
                        @endif
                        
                        <div class="absolute top-2 left-2">
                             <span class="px-2 py-0.5 bg-amber-500 text-white text-[8px] font-black uppercase tracking-widest rounded-md shadow-sm">
                                {{ $book->category->name ?? 'Umum' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Mini Cards -->
                <div class="grid grid-cols-2 gap-2.5">
                    <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase block mb-0.5 tracking-tight">Tahun</span>
                        <span class="text-xs font-bold text-gray-700">{{ $book->tahun_terbit }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-gray-100 shadow-sm text-center">
                        <span class="text-[9px] font-black text-gray-400 uppercase block mb-0.5 tracking-tight">Stok</span>
                        <span class="text-xs font-bold {{ $book->stok > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                            {{ $book->stok }} <span class="text-[9px] font-medium text-gray-400 uppercase ml-0.5">Unit</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Details & Action -->
            <div class="flex-1 space-y-5">
                <div class="bg-white rounded-2xl p-5 md:p-7 shadow-sm border border-gray-100 relative overflow-hidden">
                    <!-- Title Header -->
                    <div class="border-b border-gray-50 pb-4 mb-4">
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">{{ $book->penerbit }}</p>
                        <h2 class="text-lg md:text-xl font-black text-gray-900 leading-tight mb-2">{{ $book->judul }}</h2>
                        <div class="flex items-center gap-2 text-gray-500">
                            <div class="w-6 h-6 rounded-full bg-amber-50 flex items-center justify-center">
                                <i class="ri-user-6-line text-xs text-amber-600"></i>
                            </div>
                            <p class="text-xs font-bold italic">{{ $book->penulis }}</p>
                        </div>
                    </div>

                    <!-- Compact Description -->
                    <div class="space-y-2 mb-6">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                             Ringkasan Koleksi
                        </h4>
                        <div class="text-xs text-gray-600 leading-relaxed bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                            {{ $book->deskripsi ?: 'Informasi deskripsi belum tersedia untuk koleksi ini.' }}
                        </div>
                    </div>

                    <!-- Loan Form Section (Compact) -->
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200/60 shadow-inner">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                            <div>
                                <h4 class="text-sm font-black text-gray-900">Ajukan Pinjam</h4>
                                <p class="text-[10px] text-gray-400">Pastikan stok tersedia sebelum meminjam.</p>
                            </div>
                            
                            @php
                                $globalDenda = \App\Models\Setting::get('denda_per_hari', 1000);
                            @endphp
                            <!-- Refined Info Badge Group -->
                            <div class="flex items-center gap-3 px-3.5 py-3 bg-white rounded-lg  border border-gray-100 shadow-sm self-start sm:self-center">
                                <div class="flex flex-col items-start sm:items-end">
                                    <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest leading-none mb-0.5">Denda</span>
                                    <span class="text-[10px] font-black text-rose-600 leading-none">Rp{{ number_format($globalDenda, 0, ',', '.') }}/Hari</span>
                                </div>
                                <div class="w-px h-4 bg-gray-100"></div>
                                <div class="flex flex-col items-start sm:items-end">
                                    <span class="text-[7px] font-black text-gray-400 uppercase tracking-widest leading-none mb-0.5">Limit</span>
                                    <span class="text-[10px] font-black text-amber-600 leading-none">7 Hari</span>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('siswa.books.pinjam', $book->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3 bg-white rounded-xl border border-gray-100">
                                    <label class="text-[9px] font-black text-gray-400 uppercase block mb-1">Tgl Pinjam</label>
                                    <div class="flex items-center gap-2 text-gray-800">
                                        <i class="ri-calendar-event-line text-amber-500 text-sm"></i>
                                        <span class="text-xs font-bold">{{ now()->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="p-3 bg-white rounded-xl border border-amber-200 ring-2 ring-amber-500/5 transition-all">
                                    <label class="text-[9px] font-black text-amber-700 uppercase block mb-1">Target Kembali</label>
                                    <div class="flex items-center gap-2">
                                        <i class="ri-calendar-check-line text-amber-600 text-sm"></i>
                                        <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', now()->addDays(3)->format('Y-m-d')) }}" required 
                                            min="{{ now()->addDay()->format('Y-m-d') }}" 
                                            max="{{ now()->addDays(7)->format('Y-m-d') }}" 
                                            class="w-full bg-transparent border-none p-0 text-xs font-black text-gray-800 focus:ring-0 outline-none cursor-pointer">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-1">
                                @if($book->stok > 0)
                                    <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black rounded-xl transition-all shadow-md shadow-amber-500/10 active:scale-[0.98] flex items-center justify-center gap-2 uppercase tracking-widest">
                                        <i class="ri-send-plane-fill"></i> Ajukan Peminjaman
                                    </button>
                                @else
                                    <div class="w-full py-3 bg-gray-100 text-gray-400 text-[10px] font-black rounded-xl text-center border border-gray-200 uppercase tracking-widest">
                                        Stok Habis
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
