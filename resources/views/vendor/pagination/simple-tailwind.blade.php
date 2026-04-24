@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex gap-2 items-center justify-between">

        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-300 bg-white border border-gray-100 cursor-not-allowed rounded-xl uppercase tracking-widest">
                Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-bold text-amber-700 bg-white border border-amber-100 rounded-xl hover:bg-amber-50 transition-colors uppercase tracking-widest">
                Sebelumnya
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-bold text-amber-700 bg-white border border-amber-100 rounded-xl hover:bg-amber-50 transition-colors uppercase tracking-widest">
                Selanjutnya
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-xs font-bold text-gray-300 bg-white border border-gray-100 cursor-not-allowed rounded-xl uppercase tracking-widest">
                Selanjutnya
            </span>
        @endif

    </nav>
@endif
