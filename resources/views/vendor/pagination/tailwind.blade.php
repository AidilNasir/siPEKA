@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 py-1">
        
        {{-- Left: Data Counter Summary --}}
        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
            <span>Menampilkan</span>
            @if ($paginator->firstItem())
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg font-bold text-slate-800 bg-white border border-slate-200/90 shadow-2xs">
                    {{ $paginator->firstItem() }} &ndash; {{ $paginator->lastItem() }}
                </span>
                <span>dari</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg font-bold text-[#091E3A] bg-white border border-slate-200/90 shadow-2xs">
                    {{ $paginator->total() }}
                </span>
                <span>data</span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg font-bold text-slate-800 bg-white border border-slate-200/90 shadow-2xs">
                    {{ $paginator->count() }}
                </span>
                <span>data</span>
            @endif
        </div>

        {{-- Mobile Controls (< 640px) --}}
        <div class="flex items-center justify-between w-full sm:hidden pt-2 border-t border-slate-100">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/60 rounded-xl cursor-not-allowed">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-[#091E3A] shadow-2xs active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            <span class="text-xs font-semibold text-slate-600 px-2 py-1 bg-slate-100 rounded-lg">
                Hal {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-[#091E3A] shadow-2xs active:scale-95 transition-all">
                    <span>Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/60 rounded-xl cursor-not-allowed">
                    <span>Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop Navigation (Cool Floating Figma Pills) --}}
        <div class="hidden sm:flex items-center gap-1.5">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-300 bg-slate-100/70 border border-slate-200/50 cursor-not-allowed" aria-disabled="true" aria-label="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-600 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-100 hover:text-[#091E3A] hover:border-slate-300 active:scale-95 transition-all" aria-label="Halaman Sebelumnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
            @endif

            {{-- Numbered Page Links --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-7 h-8 flex items-center justify-center text-slate-400 font-bold text-xs select-none">
                        &hellip;
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="min-w-[32px] h-8 px-2 rounded-xl flex items-center justify-center font-bold text-xs bg-[#091E3A] text-white shadow-md shadow-[#091E3A]/20 border border-[#091E3A] cursor-default" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="min-w-[32px] h-8 px-2 rounded-xl flex items-center justify-center font-semibold text-xs text-slate-600 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-100 hover:text-[#091E3A] hover:border-slate-300 active:scale-95 transition-all" aria-label="Buka halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-600 bg-white border border-slate-200/90 shadow-2xs hover:bg-slate-100 hover:text-[#091E3A] hover:border-slate-300 active:scale-95 transition-all" aria-label="Halaman Berikutnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span class="w-8 h-8 rounded-xl flex items-center justify-center text-slate-300 bg-slate-100/70 border border-slate-200/50 cursor-not-allowed" aria-disabled="true" aria-label="Halaman Berikutnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
