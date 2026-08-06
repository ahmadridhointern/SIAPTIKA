<x-layouts.admin title="Dashboard Admin">

    {{-- Dashboard Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Ikhtisar Administrasi</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif text-4xl md:text-5xl text-[#1A1A1A] tracking-tight">
                    Dashboard Kegiatan
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Ringkasan jadwal kegiatan dan administrasi Bidang APTIKA.
                </p>
            </div>

            {{-- Waktu Sistem --}}
            <div class="card-serif p-5 bg-[#FFFFFF] self-start md:self-center shadow-sm">
                <div id="live-clock-date" class="text-[#1A1A1A] font-medium text-base leading-snug">
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }}
                </div>
                <div id="live-clock-time" class="text-[#6B6B6B] text-xs font-mono mt-1">
                    Pukul {{ now()->format('H:i:s') }} WIB
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10" data-gsap="stagger-cards">

        {{-- Card 1: Total Kegiatan --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between gsap-card transition-all duration-200" data-gsap-hover="elevate">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Total Kegiatan</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $totalKegiatan }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Total kegiatan tercatat
            </div>
        </div>

        {{-- Card 2: Hari Ini --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between gsap-card transition-all duration-200" data-gsap-hover="elevate">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Hari Ini</span>
                <span class="font-serif text-5xl {{ $kegiatanHariIni > 0 ? 'text-[#B8860B]' : 'text-[#1A1A1A]' }} font-medium leading-none">
                    {{ $kegiatanHariIni }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Agenda aktif hari ini
            </div>
        </div>

        {{-- Card 3: Mendatang --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between gsap-card transition-all duration-200" data-gsap-hover="elevate">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Mendatang</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $kegiatanMendatang }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Agenda mendatang
            </div>
        </div>

        {{-- Card 4: Total Arsip --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between gsap-card transition-all duration-200" data-gsap-hover="elevate">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Total Arsip</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $totalArsip }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Total dokumen arsip
            </div>
        </div>

    </div>

    {{-- AGENDA — Dua kolom (Hari Ini | Mendatang) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- ── Kegiatan Hari Ini ─────────────────────────────────── --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Agenda Hari Ini</h2>
                    @if($agendaHariIni->count() > 0)
                        <span class="inline-flex items-center justify-center min-w-[1.6rem] h-6 px-1.5 text-xs font-mono font-bold rounded-lg bg-[#B8860B]/10 text-[#B8860B] border border-[#B8860B]/20">
                            {{ $agendaHariIni->count() }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.activities.index', ['date_from' => $todayStr, 'date_to' => $todayStr]) }}"
                   onclick="showGlobalLoading('Memuat agenda...')"
                   class="group inline-flex items-center gap-1.5 text-[#B8860B] hover:text-[#9A7009] text-xs font-mono transition-colors"
                   title="Lihat semua">
                    <span class="max-w-0 overflow-hidden whitespace-nowrap opacity-0 group-hover:max-w-xs group-hover:opacity-100 transition-all duration-300 ease-in-out text-[0.7rem] font-medium font-sans">
                        Lihat Semua
                    </span>
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            @if($agendaHariIni->isEmpty())
                <div class="card-serif px-6 py-4 flex items-center justify-center gap-2.5 text-center" style="background-color: #FAFAF8 !important; border: 1.5px solid #DDD8D0 !important; box-shadow: none !important; min-height: 80px;">
                    <svg class="w-4 h-4 text-[#9A948D] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    <p class="text-xs text-[#6B6B6B] font-medium leading-none">Tidak ada kegiatan hari ini.</p>
                </div>
            @else
                <div class="card-serif bg-[#FFFFFF] overflow-hidden">
                    @foreach($agendaHariIni as $kegiatan)
                        <div class="px-6 py-4 {{ !$loop->last ? 'border-b border-[#F5F3F0]' : '' }} hover:bg-[#F5F3F0]/40 transition-colors duration-150">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.activities.show', $kegiatan) }}"
                                       onclick="showGlobalLoading('Memuat detail kegiatan...')"
                                       class="font-serif text-base text-[#1A1A1A] hover:text-[#B8860B] transition-colors leading-snug truncate block">
                                        {{ $kegiatan->title }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-x-6 gap-y-1 mt-1">
                                        <span class="flex items-center gap-1 text-[0.7rem] text-[#6B6B6B] font-mono">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($kegiatan->time)->format('H:i') }} WIB
                                        </span>
                                        <span class="flex items-center gap-1 text-[0.7rem] text-[#6B6B6B] font-mono">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                            </svg>
                                            {{ $kegiatan->location }}
                                        </span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-mono font-medium uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 flex-shrink-0">
                                    Hari Ini
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Kegiatan Mendatang ────────────────────────────────── --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Kegiatan Mendatang</h2>
                    @if($agendaMendatang->count() > 0)
                        <span class="inline-flex items-center justify-center min-w-[1.6rem] h-6 px-1.5 text-xs font-mono font-bold rounded-lg bg-[#B8860B]/10 text-[#B8860B] border border-[#B8860B]/20">
                            {{ $agendaMendatang->count() }}
                        </span>
                    @endif
                </div>
                <a href="{{ $upcomingMinStr && $upcomingMaxStr ? route('admin.activities.index', ['date_from' => $upcomingMinStr, 'date_to' => $upcomingMaxStr]) : route('admin.activities.index', ['status' => 'scheduled']) }}"
                   onclick="showGlobalLoading('Memuat agenda...')"
                   class="group inline-flex items-center gap-1.5 text-[#B8860B] hover:text-[#9A7009] text-xs font-mono transition-colors"
                   title="Lihat semua">
                    <span class="max-w-0 overflow-hidden whitespace-nowrap opacity-0 group-hover:max-w-xs group-hover:opacity-100 transition-all duration-300 ease-in-out text-[0.7rem] font-medium font-sans">
                        Lihat Semua
                    </span>
                    <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            </div>

            @if($agendaMendatang->isEmpty())
                <div class="card-serif px-6 py-4 flex items-center justify-center gap-2.5 text-center" style="background-color: #FAFAF8 !important; border: 1.5px solid #DDD8D0 !important; box-shadow: none !important; min-height: 80px;">
                    <svg class="w-4 h-4 text-[#9A948D] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                    </svg>
                    <p class="text-xs text-[#6B6B6B] font-medium leading-none">Belum ada kegiatan mendatang terjadwal.</p>
                </div>
            @else
                <div class="card-serif bg-[#FFFFFF] overflow-hidden">
                    @foreach($agendaMendatang as $kegiatan)
                        @php
                            $diffDays = (int) now()->startOfDay()->diffInDays(
                                \Carbon\Carbon::parse($kegiatan->activity_date)->startOfDay(),
                                false
                            );
                        @endphp
                        <div class="px-6 py-4 {{ !$loop->last ? 'border-b border-[#F5F3F0]' : '' }} hover:bg-[#F5F3F0]/40 transition-colors duration-150">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.activities.show', $kegiatan) }}"
                                       onclick="showGlobalLoading('Memuat detail kegiatan...')"
                                       class="font-serif text-base text-[#1A1A1A] hover:text-[#B8860B] transition-colors leading-snug truncate block">
                                        {{ $kegiatan->title }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1">
                                        <span class="flex items-center gap-1 text-[0.7rem] text-[#6B6B6B] font-mono">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                            </svg>
                                            {{ $kegiatan->activity_date->isoFormat('D MMM YYYY') }}
                                        </span>
                                        <span class="flex items-center gap-1 text-[0.7rem] text-[#6B6B6B] font-mono">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($kegiatan->time)->format('H:i') }} WIB
                                        </span>
                                        <span class="flex items-center gap-1 text-[0.7rem] text-[#6B6B6B] font-mono">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                            </svg>
                                            {{ $kegiatan->location }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-mono font-medium uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                                        {{ $diffDays }} hari lagi
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Live Clock Script --}}
    <script>
        (function () {
            const dateEl = document.getElementById('live-clock-date');
            const timeEl = document.getElementById('live-clock-time');
            if (!dateEl || !timeEl) return;

            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            function updateClock() {
                const now  = new Date();
                const h    = String(now.getHours()).padStart(2, '0');
                const m    = String(now.getMinutes()).padStart(2, '0');
                const s    = String(now.getSeconds()).padStart(2, '0');
                dateEl.textContent = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
                timeEl.textContent = `Pukul ${h}:${m}:${s} WIB`;
            }

            setInterval(updateClock, 1000);
            updateClock();
        })();
    </script>

</x-layouts.admin>
