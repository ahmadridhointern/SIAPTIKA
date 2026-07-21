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
                    Pantau jadwal kegiatan dan administrasi Bidang APTIKA secara real-time.
                </p>
            </div>
            <div class="text-xs font-mono text-[#6B6B6B] self-start md:self-end flex items-center gap-2 pb-1">
                <span class="small-caps text-[0.68rem] text-[#B8860B]">Waktu Sistem:</span>
                <span id="live-clock" class="text-[#1A1A1A] font-medium">
                    {{ now()->isoFormat('dddd, D MMMM YYYY') }} — {{ now()->format('H:i:s') }} WIB
                </span>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        {{-- Card 1: Total Kegiatan --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Total Kegiatan</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $totalKegiatan }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Seluruh kegiatan terdaftar
            </div>
        </div>

        {{-- Card 2: Hari Ini --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Hari Ini</span>
                <span class="font-serif text-5xl text-[#B8860B] font-medium leading-none">
                    {{ $kegiatanHariIni }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Kegiatan yang berlangsung hari ini
            </div>
        </div>

        {{-- Card 3: Mendatang --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Mendatang</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $kegiatanMendatang }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Jadwal kegiatan masa depan
            </div>
        </div>

        {{-- Card 4: Selesai --}}
        <div class="card-serif card-serif-accent p-6 flex flex-col justify-between">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-2">Telah Selesai</span>
                <span class="font-serif text-5xl text-[#1A1A1A] font-light leading-none">
                    {{ $kegiatanSelesai }}
                </span>
            </div>
            <div class="mt-4 pt-4 border-t border-[#F5F3F0] text-xs text-[#6B6B6B]">
                Kegiatan yang sudah terlewati
            </div>
        </div>

    </div>

    {{-- Main Content Section: Full Width --}}
    <div class="space-y-6">
        
        <div class="flex items-center justify-between">
            <h2 class="font-serif text-2xl text-[#1A1A1A]">
                Kegiatan Terbaru
            </h2>
            <span class="small-caps text-[0.65rem]">10 Entri Terakhir</span>
        </div>

        <div class="card-serif bg-[#FFFFFF] overflow-hidden">
            @if($kegiatanTerbaru->isEmpty())
                <div class="p-8 text-center text-[#6B6B6B] text-sm">
                    Belum ada kegiatan yang terdaftar dalam sistem.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                                <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Kegiatan</th>
                                <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Jadwal</th>
                                <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Tempat</th>
                                <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatanTerbaru as $kegiatan)
                                <tr class="border-b border-[#E8E4DF] hover:bg-[#F5F3F0]/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-serif text-base text-[#1A1A1A] font-semibold">
                                            {{ $kegiatan->title }}
                                        </div>
                                        <div class="text-xs text-[#6B6B6B] line-clamp-1 mt-0.5">
                                            {{ $kegiatan->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#1A1A1A]">
                                        <div>{{ $kegiatan->activity_date->isoFormat('D MMM YYYY') }}</div>
                                        <div class="text-xs text-[#6B6B6B] font-mono mt-0.5">
                                            {{ \Carbon\Carbon::parse($kegiatan->time)->format('H:i') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#6B6B6B]">
                                        {{ $kegiatan->location }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $kegiatanDate = $kegiatan->activity_date->toDateString();
                                            $todayDate = today()->toDateString();
                                        @endphp

                                        @if($kegiatanDate === $todayDate)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                                Hari Ini
                                            </span>
                                        @elseif($kegiatanDate > $todayDate)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                                                Mendatang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Live Clock Script --}}
    <script>
        (function () {
            const clockEl = document.getElementById('live-clock');
            if (!clockEl) return;

            // Mapping hari dan bulan Indonesia untuk akurasi penuh
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            function updateClock() {
                const now = new Date();
                const dayName = days[now.getDay()];
                const day = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();

                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                clockEl.textContent = `${dayName}, ${day} ${monthName} ${year} — ${hours}:${minutes}:${seconds} WIB`;
            }

            setInterval(updateClock, 1000);
            // Panggil sekali untuk sinkronisasi instan saat load halaman
            updateClock();
        })();
    </script>

</x-layouts.admin>
