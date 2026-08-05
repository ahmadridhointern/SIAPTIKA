@php
    $activeFilters = [];

    if (request('status') === 'scheduled')   $activeFilters[] = 'Status: Direncana';
    if (request('status') === 'ongoing')     $activeFilters[] = 'Status: Berlangsung';
    if (request('status') === 'completed')   $activeFilters[] = 'Status: Selesai';

    if (request('location')) {
        $activeFilters[] = 'Lokasi Kegiatan: ' . request('location');
    }

    if (request('date_from') && request('date_to')) {
        if (request('date_from') === request('date_to')) {
            $activeFilters[] = 'Tanggal: ' . \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y');
        } else {
            $activeFilters[] = 'Tanggal: ' . \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') . ' – ' . \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y');
        }
    }

    if (request('sort') === 'oldest')        $activeFilters[] = 'Urutan: Terlama';
    if (request('sort') === 'az')            $activeFilters[] = 'Urutan: A → Z';

    if (request('has_documents') === '1')    $activeFilters[] = 'Ada Arsip';
@endphp

{{-- Result Summary --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 px-1" role="status" aria-live="polite">
    <p class="text-xs font-mono text-[#6B6B6B]">
        @if($activities->total() === 0)
            Tidak ada kegiatan ditemukan
        @else
            Menampilkan
            <span class="font-semibold text-[#1A1A1A]">{{ $activities->firstItem() }}–{{ $activities->lastItem() }}</span>
            dari
            <span class="font-semibold text-[#1A1A1A]">{{ $activities->total() }}</span> kegiatan
            @if(request('search'))
                untuk <span class="text-[#B8860B] font-semibold">"{{ request('search') }}"</span>
            @endif
        @endif
    </p>
    @if(count($activeFilters) > 0 || request('search'))
        <div class="flex flex-wrap items-center gap-1.5 text-xs font-mono">
            <span class="inline-flex items-center gap-1 text-[#B8860B] font-semibold">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.553.894l-4 2A1 1 0 016 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
                Filter Aktif:
            </span>
            @foreach($activeFilters as $filterLabel)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] bg-[rgba(184,134,11,0.08)] text-[#B8860B] border border-[rgba(184,134,11,0.2)] font-mono">
                    {{ $filterLabel }}
                </span>
            @endforeach
        </div>
    @endif
</div>


{{-- ================================================================
     TABLE — Desktop
     ================================================================ --}}
<div class="card-serif bg-[#FFFFFF] overflow-hidden mb-4 hidden md:block">
    @if($activities->isEmpty())
        {{-- Empty State — Desktop --}}
        <div class="py-20 text-center px-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#F5F3F0] mb-5">
                <svg class="w-8 h-8 text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
            </div>
            <p class="font-serif text-xl text-[#1A1A1A] mb-2">
                @if(request()->anyFilled(['search', 'status']))
                    Tidak ada kegiatan yang cocok
                @else
                    Belum ada kegiatan
                @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-6 max-w-xs mx-auto leading-relaxed">
                @if(request()->anyFilled(['search', 'status']))
                    Coba ubah kata kunci pencarian atau hapus filter yang sedang aktif.
                @else
                    Belum ada kegiatan yang terdaftar dalam sistem.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('employee.activities.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Tampilkan semua kegiatan
                </a>
            @endif
        </div>
    @else
        <table class="w-full text-left border-collapse" role="table" aria-label="Daftar kegiatan">
            <thead>
                <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Judul</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium whitespace-nowrap">Tanggal &amp; Waktu</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Tempat</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8E4DF]">
                @foreach($activities as $activity)
                    <tr class="hover:bg-[#F5F3F0]/50 transition-colors duration-150" role="row">

                        {{-- Judul --}}
                        <td class="px-6 py-4" style="max-width: 280px; min-width: 0;">
                            <a href="{{ route('employee.activities.show', $activity) }}"
                               onclick="showGlobalLoading('Memuat detail kegiatan...')"
                               class="font-serif text-base text-[#1A1A1A] tracking-tight hover:text-[#B8860B] transition-colors duration-150 line-clamp-1 block"
                               title="{{ $activity->title }}"
                               aria-label="Lihat detail: {{ $activity->title }}">
                                {{ $activity->title }}
                            </a>
                            @if($activity->description)
                                <div class="text-xs text-[#6B6B6B] line-clamp-1 mt-0.5 leading-relaxed">
                                    {{ $activity->description }}
                                </div>
                            @endif
                            @if($activity->documents_count > 0)
                                <span class="inline-flex items-center gap-1 mt-1 text-[0.65rem] font-mono text-[#B8860B]"
                                      aria-label="{{ $activity->documents_count }} dokumen tersedia">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $activity->documents_count }} dokumen
                                </span>
                            @endif
                        </td>

                        {{-- Tanggal & Waktu --}}
                        <td class="px-6 py-4 text-sm text-[#1A1A1A] whitespace-nowrap">
                            @php
                                $activityDateTime = \Carbon\Carbon::parse($activity->activity_date->toDateString() . ' ' . $activity->time);
                                $now = now();
                                $diffInSeconds = $now->diffInSeconds($activityDateTime, false);
                                $isFuture = $diffInSeconds > 0;
                                $absSeconds = abs($diffInSeconds);

                                $n = 0;
                                $unit = '';

                                if ($absSeconds < 60) {
                                    $n = $absSeconds;
                                    $unit = 'DETIK';
                                } elseif ($absSeconds < 3600) {
                                    $n = floor($absSeconds / 60);
                                    $unit = 'MENIT';
                                } elseif ($absSeconds < 86400) {
                                    $n = floor($absSeconds / 3600);
                                    $unit = 'JAM';
                                } elseif ($absSeconds < 604800) {
                                    $n = floor($absSeconds / 86400);
                                    $unit = 'HARI';
                                } elseif ($absSeconds < 2592000) {
                                    $n = floor($absSeconds / 604800);
                                    $unit = 'MINGGU';
                                } elseif ($absSeconds < 31536000) {
                                    $n = floor($absSeconds / 2592000);
                                    $unit = 'BULAN';
                                } else {
                                    $n = floor($absSeconds / 31536000);
                                    $unit = 'TAHUN';
                                }
                            @endphp
                            <div class="time-hover-wrapper">
                                <div class="time-default-content">
                                    <div class="font-medium">
                                        {{ $activity->activity_date->isoFormat('D MMM YYYY') }}
                                    </div>
                                    <div class="text-xs text-[#6B6B6B] font-mono mt-0.5">
                                        <time datetime="{{ $activity->activity_date->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}">
                                            {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                                        </time>
                                    </div>
                                </div>
                                <div class="time-hover-content flex flex-col justify-center">
                                    @if($isFuture)
                                        <div class="text-[0.7rem] leading-none font-mono font-bold uppercase tracking-wider text-[#B8860B]">
                                            {{ $n }} {{ $unit }}
                                        </div>
                                        <div class="text-[0.52rem] leading-none font-mono text-[#6B6B6B] uppercase tracking-wider mt-1">
                                            DARI SEKARANG
                                        </div>
                                    @else
                                        <div class="text-[0.7rem] leading-none font-mono font-bold uppercase tracking-wider text-[#B8860B]">
                                            {{ $n }} {{ $unit }} LALU
                                        </div>
                                        <div class="text-[0.52rem] leading-none font-mono text-[#6B6B6B] uppercase tracking-wider mt-1">
                                            DIMULAI
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Tempat --}}
                        <td class="px-6 py-4 text-sm text-[#6B6B6B]" style="max-width: 180px;">
                            <span class="line-clamp-2 leading-snug">{{ $activity->location }}</span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->computed_status === 'Selesai')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Selesai
                                </span>
                            @elseif($activity->computed_status === 'Sudah Berlangsung')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Sudah Berlangsung
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Direncana
                                </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ================================================================
     CARDS — Mobile
     ================================================================ --}}
<div class="space-y-3 mb-4 md:hidden">
    @if($activities->isEmpty())
        {{-- Empty State — Mobile --}}
        <div class="card-serif p-10 text-center bg-[#FFFFFF]">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F5F3F0] mb-4">
                <svg class="w-7 h-7 text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
            </div>
            <p class="font-serif text-lg text-[#1A1A1A] mb-2">
                @if(request()->anyFilled(['search', 'status'])) Tidak ada yang cocok @else Belum ada kegiatan @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-5 leading-relaxed">
                @if(request()->anyFilled(['search', 'status']))
                    Coba ubah kata kunci atau hapus filter.
                @else
                    Belum ada kegiatan yang terdaftar dalam sistem.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('employee.activities.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Tampilkan semua kegiatan
                </a>
            @endif
        </div>
    @else
        @foreach($activities as $activity)
            <div class="card-serif p-5 bg-[#FFFFFF] hover:shadow-sm transition-shadow duration-150">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <a href="{{ route('employee.activities.show', $activity) }}"
                       onclick="showGlobalLoading('Memuat detail kegiatan...')"
                       class="font-serif text-base text-[#1A1A1A] tracking-tight hover:text-[#B8860B] transition-colors leading-snug flex-1 min-w-0"
                       aria-label="Lihat detail: {{ $activity->title }}">
                        {{ $activity->title }}
                    </a>
                    {{-- Status Badge --}}
                    @if($activity->computed_status === 'Selesai')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Selesai
                        </span>
                    @elseif($activity->computed_status === 'Sudah Berlangsung')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Sudah Berlangsung
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Direncana
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                    <div>
                        <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Tanggal</span>
                        <div class="text-[#1A1A1A] font-medium mt-0.5 leading-snug">
                            {{ $activity->activity_date->isoFormat('D MMM YYYY') }}
                        </div>
                    </div>
                    <div>
                        <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Waktu</span>
                        <div class="text-[#1A1A1A] font-medium mt-0.5">
                            <time datetime="{{ $activity->activity_date->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}">
                                {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                            </time>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Tempat</span>
                        <div class="text-[#1A1A1A] mt-0.5 leading-snug">{{ $activity->location }}</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#F5F3F0] flex items-center justify-between">
                    @if($activity->documents_count > 0)
                        <span class="inline-flex items-center gap-1 text-[0.65rem] font-mono text-[#B8860B]"
                              aria-label="{{ $activity->documents_count }} dokumen tersedia">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd"/>
                            </svg>
                            {{ $activity->documents_count }} dokumen tersedia
                        </span>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('employee.activities.show', $activity) }}"
                       onclick="showGlobalLoading('Memuat detail kegiatan...')"
                       class="inline-flex items-center gap-1 text-[0.68rem] font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors"
                       aria-label="Lihat detail kegiatan {{ $activity->title }}">
                        Lihat Detail
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Pagination --}}
@if($activities->hasPages())
    <div class="mt-4" role="navigation" aria-label="Navigasi halaman kegiatan">
        {{ $activities->links() }}
    </div>
@endif
