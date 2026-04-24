@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman">

        {{-- Mobile: Previous / Next --}}
        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-300 bg-white border border-gray-100 rounded-xl cursor-not-allowed uppercase tracking-widest">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-bold text-amber-700 bg-white border border-amber-100 rounded-xl hover:bg-amber-50 transition-colors uppercase tracking-widest">
                    Sebelumnya
                </a>
            @endif

            <span class="text-xs text-gray-400 font-medium">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-bold text-amber-700 bg-white border border-amber-100 rounded-xl hover:bg-amber-50 transition-colors uppercase tracking-widest">
                    Selanjutnya
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-300 bg-white border border-gray-100 rounded-xl cursor-not-allowed uppercase tracking-widest">
                    Selanjutnya
                </span>
            @endif
        </div>

        {{-- Desktop: Full pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-gray-400 font-medium">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-bold text-gray-700">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-bold text-gray-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-bold text-gray-700">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1 rtl:flex-row-reverse">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Sebelumnya">
                            <span class="inline-flex items-center justify-center w-9 h-9 text-gray-300 bg-white border border-gray-100 cursor-not-allowed rounded-lg" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-gray-500 bg-white border border-gray-100 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-200 transition-all duration-200" aria-label="Sebelumnya">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-gray-300 cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-black text-white bg-amber-500 border border-amber-500 rounded-lg shadow-sm shadow-amber-200 cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-gray-600 bg-white border border-gray-100 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-200 transition-all duration-200" aria-label="Halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-gray-500 bg-white border border-gray-100 rounded-lg hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 focus:outline-none focus:ring-2 focus:ring-amber-200 transition-all duration-200" aria-label="Selanjutnya">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Selanjutnya">
                            <span class="inline-flex items-center justify-center w-9 h-9 text-gray-300 bg-white border border-gray-100 cursor-not-allowed rounded-lg" aria-hidden="true">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
