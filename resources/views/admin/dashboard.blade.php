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
            <div class="text-xs font-mono text-[#6B6B6B] border border-[#E8E4DF] rounded-md px-3 py-1.5 bg-[#FFFFFF] shadow-sm">
                Hari ini: <span class="text-[#B8860B] font-medium">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
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

    {{-- Main Content Section: Asymmetric Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: 5 Kegiatan Terbaru --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-2xl text-[#1A1A1A]">
                    Kegiatan Terbaru
                </h2>
                <span class="small-caps text-[0.65rem]">5 Entri Terakhir</span>
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

        {{-- Right: Quick Action / Informational Panel --}}
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-2xl text-[#1A1A1A]">
                    Navigasi Cepat
                </h2>
            </div>
            
            <div class="card-serif p-6 space-y-6 bg-[#FFFFFF]">
                <div>
                    <h3 class="font-serif text-lg text-[#1A1A1A] mb-2">Administrasi Kegiatan</h3>
                    <p class="text-xs text-[#6B6B6B] leading-relaxed mb-4">
                        Kelola data kegiatan Bidang APTIKA secara penuh. Anda dapat menambah, mengubah jadwal, dan mengarsipkan dokumen pendukung.
                    </p>
                    <hr class="rule-line my-4">
                    <div class="space-y-3">
                        <a href="{{ route('admin.activities.index', ['create' => 1]) }}"
                           class="btn-primary w-full text-center text-xs justify-center">
                            + Tambah Kegiatan Baru
                        </a>
                        <a href="{{ route('admin.activities.index') }}"
                           class="block text-center text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200">
                            Lihat Semua Jadwal
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-[#E8E4DF] text-[0.7rem] text-[#6B6B6B] font-mono space-y-1">
                    <div>User: <span class="text-[#1A1A1A]">{{ Auth::user()->email }}</span></div>
                    <div>Level: <span class="text-[#1A1A1A]">Administrator Utama</span></div>
                </div>
            </div>
        </div>

    </div>

</x-layouts.admin>
