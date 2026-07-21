<x-layouts.admin title="Daftar Kegiatan">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif text-4xl text-[#1A1A1A] tracking-tight">
                    Jadwal Kegiatan
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Kelola, pantau, dan publikasikan seluruh kegiatan Bidang APTIKA.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.activities.create') }}" class="btn-primary">
                    + Tambah Kegiatan
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert-success mb-6">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error mb-6">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Search & Filter Bar --}}
    <div class="card-serif p-5 mb-6 bg-[#FFFFFF]">
        <form method="GET" action="{{ route('admin.activities.index') }}"
              class="flex flex-col md:flex-row gap-3 items-stretch md:items-end">

            {{-- Search Input --}}
            <div class="flex-1">
                <label for="search" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                    Cari Kegiatan
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#6B6B6B]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                        </svg>
                    </span>
                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Judul atau tempat kegiatan..."
                        class="input-serif"
                        style="padding-left: 2.5rem;"
                        autocomplete="off"
                    >
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-full md:w-52">
                <label for="status" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                    Status
                </label>
                <select id="status" name="status" class="input-serif">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>
                        Direncana
                    </option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                        Selesai
                    </option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2 w-full md:w-auto flex-shrink-0">
                <button type="submit" class="btn-primary flex-1 md:flex-none md:px-6" style="min-height: 3rem;">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.activities.index') }}"
                       class="inline-flex items-center justify-center px-4 rounded border border-[#E8E4DF] text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200 flex-1 md:flex-none whitespace-nowrap"
                       style="min-height: 3rem;">
                        ✕ Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Result Summary --}}
    <div class="flex items-center justify-between mb-3 px-1">
        <p class="text-xs font-mono text-[#6B6B6B]">
            Menampilkan
            <span class="font-semibold text-[#1A1A1A]">{{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-[#1A1A1A]">{{ $activities->total() }}</span>
            kegiatan
            @if(request('search'))
                untuk pencarian <span class="text-[#B8860B] font-semibold">"{{ request('search') }}"</span>
            @endif
        </p>
        @if(request()->anyFilled(['search', 'status']))
            <span class="inline-flex items-center gap-1.5 text-xs font-mono text-[#B8860B]">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.553.894l-4 2A1 1 0 016 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
                Filter Aktif
            </span>
        @endif
    </div>

    {{-- Activities Table (Desktop) --}}
    <div class="card-serif bg-[#FFFFFF] overflow-hidden mb-4 hidden md:block">
        @if($activities->isEmpty())
            {{-- Empty State --}}
            <div class="py-20 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F5F3F0] mb-4">
                    <svg class="w-7 h-7 text-[#6B6B6B]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                    </svg>
                </div>
                <p class="font-serif text-lg text-[#1A1A1A] mb-1">
                    @if(request()->anyFilled(['search', 'status']))
                        Tidak ada kegiatan yang cocok
                    @else
                        Belum ada kegiatan
                    @endif
                </p>
                <p class="text-sm text-[#6B6B6B] mb-6">
                    @if(request()->anyFilled(['search', 'status']))
                        Coba ubah kata kunci pencarian atau hapus filter yang aktif.
                    @else
                        Mulai tambahkan kegiatan pertama Bidang APTIKA.
                    @endif
                </p>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.activities.index') }}"
                       class="inline-flex items-center text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors duration-150">
                        ← Tampilkan semua kegiatan
                    </a>
                @else
                    <a href="{{ route('admin.activities.create') }}" class="btn-primary">
                        + Tambah Kegiatan Pertama
                    </a>
                @endif
            </div>
        @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                        <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">
                            Judul
                        </th>
                        <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium whitespace-nowrap">
                            Tanggal & Waktu
                        </th>
                        <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">
                            Tempat
                        </th>
                        <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">
                            Status
                        </th>
                        <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E8E4DF]">
                    @foreach($activities as $activity)
                        @php
                            $isPast = $activity->activity_date->lt(today());
                            $hasDocuments = $activity->documents_count > 0; // from withCount()
                        @endphp
                        <tr class="hover:bg-[#F5F3F0]/50 transition-colors duration-150">

                            {{-- Judul & Deskripsi --}}
                            <td class="px-6 py-4 max-w-xs">
                                <a href="{{ route('admin.activities.show', $activity->id) }}"
                                   class="font-serif text-base text-[#1A1A1A] font-semibold hover:text-[#B8860B] transition-colors duration-150 line-clamp-1 block">
                                    {{ $activity->title }}
                                </a>
                                @if($activity->description)
                                    <div class="text-xs text-[#6B6B6B] line-clamp-1 mt-0.5">
                                        {{ $activity->description }}
                                    </div>
                                @endif
                                @if($hasDocuments)
                                    <span class="inline-flex items-center gap-1 mt-1 text-[0.65rem] font-mono text-[#B8860B]">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd"/></svg>
                                        {{ $activity->documents_count }} dokumen
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal & Waktu --}}
                            <td class="px-6 py-4 text-sm text-[#1A1A1A] whitespace-nowrap">
                                <div class="font-medium">{{ $activity->activity_date->isoFormat('D MMM YYYY') }}</div>
                                <div class="text-xs text-[#6B6B6B] font-mono mt-0.5">
                                    {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                                </div>
                            </td>

                            {{-- Tempat --}}
                            <td class="px-6 py-4 text-sm text-[#6B6B6B] max-w-[180px]">
                                <span class="line-clamp-2 leading-snug">{{ $activity->location }}</span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($activity->status === 'completed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
                                        Selesai
                                    </span>
                                @elseif($activity->activity_date->isToday())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse flex-shrink-0"></span>
                                        Hari Ini
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 flex-shrink-0"></span>
                                        Direncana
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-3">

                                    <a href="{{ route('admin.activities.show', $activity->id) }}"
                                       class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                                       title="Lihat Detail">
                                        Detail
                                    </a>

                                    @if(!$isPast)
                                        <span class="text-[#E8E4DF]">|</span>
                                        <a href="{{ route('admin.activities.edit', $activity->id) }}"
                                           class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                                           title="Ubah Kegiatan">
                                            Ubah
                                        </a>

                                        @if(!$hasDocuments)
                                            <span class="text-[#E8E4DF]">|</span>
                                            <form action="{{ route('admin.activities.destroy', $activity->id) }}"
                                                  method="POST" class="inline"
                                                  onsubmit="return confirm('Hapus kegiatan \'{{ addslashes($activity->title) }}\'?\nTindakan ini tidak dapat dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-xs font-mono font-semibold text-red-500 hover:text-red-700 transition-colors duration-150 cursor-pointer"
                                                        style="background: none; border: none; padding: 0;"
                                                        title="Hapus Kegiatan">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Activities Cards (Mobile) --}}
    <div class="space-y-3 mb-4 md:hidden">
        @if($activities->isEmpty())
            <div class="card-serif p-10 text-center bg-[#FFFFFF]">
                <p class="font-serif text-lg text-[#1A1A1A] mb-1">
                    @if(request()->anyFilled(['search', 'status']))
                        Tidak ada kegiatan yang cocok
                    @else
                        Belum ada kegiatan
                    @endif
                </p>
                <p class="text-sm text-[#6B6B6B] mb-4">
                    @if(request()->anyFilled(['search', 'status']))
                        Coba ubah kata kunci atau hapus filter.
                    @else
                        Tambahkan kegiatan pertama Bidang APTIKA.
                    @endif
                </p>
                @if(!request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.activities.create') }}" class="btn-primary">
                        + Tambah Kegiatan
                    </a>
                @endif
            </div>
        @else
            @foreach($activities as $activity)
                @php
                    $isPast = $activity->activity_date->lt(today());
                    $hasDocuments = $activity->documents_count > 0;
                @endphp
                <div class="card-serif p-5 bg-[#FFFFFF]">
                    {{-- Header Row --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <a href="{{ route('admin.activities.show', $activity->id) }}"
                           class="font-serif text-base text-[#1A1A1A] font-semibold hover:text-[#B8860B] transition-colors leading-snug flex-1">
                            {{ $activity->title }}
                        </a>
                        {{-- Status Badge Mobile --}}
                        @if($activity->status === 'completed')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200 flex-shrink-0">
                                Selesai
                            </span>
                        @elseif($activity->activity_date->isToday())
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 flex-shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Hari Ini
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">
                                Direncana
                            </span>
                        @endif
                    </div>

                    {{-- Meta --}}
                    <div class="grid grid-cols-2 gap-2 mb-3 text-xs">
                        <div>
                            <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Tanggal</span>
                            <div class="text-[#1A1A1A] font-medium mt-0.5">{{ $activity->activity_date->isoFormat('D MMM YYYY') }}</div>
                        </div>
                        <div>
                            <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Waktu</span>
                            <div class="text-[#1A1A1A] font-medium mt-0.5">{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB</div>
                        </div>
                        <div class="col-span-2">
                            <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Tempat</span>
                            <div class="text-[#1A1A1A] mt-0.5">{{ $activity->location }}</div>
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-center gap-3 pt-3 border-t border-[#F5F3F0]">
                        <a href="{{ route('admin.activities.show', $activity->id) }}"
                           class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                            Detail
                        </a>
                        @if(!$isPast)
                            <span class="text-[#E8E4DF]">|</span>
                            <a href="{{ route('admin.activities.edit', $activity->id) }}"
                               class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                                Ubah
                            </a>
                            @if(!$hasDocuments)
                                <span class="text-[#E8E4DF]">|</span>
                                <form action="{{ route('admin.activities.destroy', $activity->id) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Hapus kegiatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs font-mono font-semibold text-red-500 hover:text-red-700 transition-colors duration-150 cursor-pointer"
                                            style="background: none; border: none; padding: 0;">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Pagination --}}
    @if($activities->hasPages())
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
    @endif

</x-layouts.admin>
