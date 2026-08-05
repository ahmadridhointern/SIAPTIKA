@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between py-3">
        {{-- Tampilan Mobile (Ringkas & Responsive) --}}
        <div class="flex items-center justify-between w-full sm:hidden gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-mono font-semibold text-[#A39E93] bg-[#FAFAF8] border border-[#E8E4DF] rounded-xl opacity-60 cursor-not-allowed select-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    Sblm
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-mono font-semibold text-[#1A1A1A] bg-[#FFFFFF] hover:bg-[#F5F3F0] hover:text-[#B8860B] border border-[#E8E4DF] rounded-xl transition-all shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    Sblm
                </a>
            @endif

            <span class="text-xs font-mono text-[#6B6B6B] px-2">
                <span class="font-bold text-[#1A1A1A]">{{ $paginator->currentPage() }}</span> / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-mono font-semibold text-[#1A1A1A] bg-[#FFFFFF] hover:bg-[#F5F3F0] hover:text-[#B8860B] border border-[#E8E4DF] rounded-xl transition-all shadow-2xs">
                    Slnjt
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-mono font-semibold text-[#A39E93] bg-[#FAFAF8] border border-[#E8E4DF] rounded-xl opacity-60 cursor-not-allowed select-none">
                    Slnjt
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </span>
            @endif
        </div>

        {{-- Tampilan Desktop (Angka Halaman Lengkap) --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-mono text-[#6B6B6B]">
                    Halaman <span class="font-bold text-[#1A1A1A]">{{ $paginator->currentPage() }}</span> dari <span class="font-bold text-[#1A1A1A]">{{ $paginator->lastPage() }}</span>
                </p>
            </div>

            <div class="flex items-center gap-1.5">
                {{-- Tombol Sebelumnya --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Halaman Sebelumnya">
                        <span class="w-9 h-9 flex items-center justify-center text-xs font-mono rounded-xl bg-[#FAFAF8] text-[#A39E93] border border-[#E8E4DF] cursor-not-allowed select-none opacity-60" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 flex items-center justify-center text-xs font-mono rounded-xl bg-[#FFFFFF] text-[#1A1A1A] hover:bg-[#F5F3F0] hover:text-[#B8860B] border border-[#E8E4DF] transition-all duration-150 shadow-2xs" aria-label="Halaman Sebelumnya" title="Sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </a>
                @endif

                {{-- Angka Halaman --}}
                @foreach ($elements as $element)
                    {{-- Separator "..." --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span class="w-9 h-9 flex items-center justify-center text-xs font-mono text-[#6B6B6B] select-none">{{ $element }}</span>
                        </span>
                    @endif

                    {{-- Tombol Angka --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span class="w-9 h-9 flex items-center justify-center text-xs font-mono font-bold rounded-xl bg-[#B8860B] text-white border border-[#B8860B] shadow-xs select-none">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center text-xs font-mono font-medium rounded-xl bg-[#FFFFFF] text-[#1A1A1A] hover:bg-[#F5F3F0] hover:text-[#B8860B] border border-[#E8E4DF] transition-all duration-150 shadow-2xs" aria-label="Buka Halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Tombol Berikutnya --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 flex items-center justify-center text-xs font-mono rounded-xl bg-[#FFFFFF] text-[#1A1A1A] hover:bg-[#F5F3F0] hover:text-[#B8860B] border border-[#E8E4DF] transition-all duration-150 shadow-2xs" aria-label="Halaman Berikutnya" title="Berikutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="Halaman Berikutnya">
                        <span class="w-9 h-9 flex items-center justify-center text-xs font-mono rounded-xl bg-[#FAFAF8] text-[#A39E93] border border-[#E8E4DF] cursor-not-allowed select-none opacity-60" aria-hidden="true">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </span>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
