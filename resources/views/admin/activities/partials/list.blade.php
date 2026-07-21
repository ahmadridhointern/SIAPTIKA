{{-- Result Summary --}}
<div class="flex items-center justify-between mb-3 px-1">
    <p class="text-xs font-mono text-[#6B6B6B]">
        Menampilkan
        <span class="font-semibold text-[#1A1A1A]">{{ $activities->firstItem() ?? 0 }}–{{ $activities->lastItem() ?? 0 }}</span>
        dari
        <span class="font-semibold text-[#1A1A1A]">{{ $activities->total() }}</span> kegiatan
        @if(request('search'))
            untuk <span class="text-[#B8860B] font-semibold">"{{ request('search') }}"</span>
        @endif
    </p>
    @if(request()->anyFilled(['search', 'status']))
        <span class="inline-flex items-center gap-1 text-xs font-mono text-[#B8860B]">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.553.894l-4 2A1 1 0 016 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
            Filter Aktif
        </span>
    @endif
</div>

{{-- ================================================================
     TABLE — Desktop
     ================================================================ --}}
<div class="card-serif bg-[#FFFFFF] overflow-hidden mb-4 hidden md:block">
    @if($activities->isEmpty())
        {{-- Empty State --}}
        <div class="py-20 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F5F3F0] mb-4">
                <svg class="w-7 h-7 text-[#6B6B6B]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </svg>
            </div>
            <p class="font-serif text-lg text-[#1A1A1A] mb-1">
                @if(request()->anyFilled(['search', 'status'])) Tidak ada kegiatan yang cocok @else Belum ada kegiatan @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-5">
                @if(request()->anyFilled(['search', 'status'])) Coba ubah kata kunci atau hapus filter yang aktif. @else Mulai tambahkan kegiatan pertama Bidang APTIKA. @endif
            </p>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.activities.index') }}" class="text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors">← Tampilkan semua kegiatan</a>
            @else
                <button onclick="openModal('create-modal')" class="btn-primary">+ Tambah Kegiatan Pertama</button>
            @endif
        </div>
    @else
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                    <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Judul</th>
                    <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium whitespace-nowrap">Tanggal & Waktu</th>
                    <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Tempat</th>
                    <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Status</th>
                    <th class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8E4DF]">
                @foreach($activities as $activity)
                    @php
                        $isPast = $activity->activity_date->lt(today());
                        $hasDocuments = $activity->documents_count > 0;
                    @endphp
                    <tr class="hover:bg-[#F5F3F0]/50 transition-colors duration-150">

                        <td class="px-6 py-4 max-w-xs">
                            <a href="{{ route('admin.activities.show', $activity->id) }}"
                               class="font-serif text-base text-[#1A1A1A] font-semibold hover:text-[#B8860B] transition-colors duration-150 line-clamp-1 block">
                                {{ $activity->title }}
                            </a>
                            @if($activity->description)
                                <div class="text-xs text-[#6B6B6B] line-clamp-1 mt-0.5">{{ $activity->description }}</div>
                            @endif
                            @if($hasDocuments)
                                <span class="inline-flex items-center gap-1 mt-1 text-[0.65rem] font-mono text-[#B8860B]">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd"/></svg>
                                    {{ $activity->documents_count }} dokumen
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-[#1A1A1A] whitespace-nowrap">
                            <div class="font-medium">{{ $activity->activity_date->isoFormat('D MMM YYYY') }}</div>
                            <div class="text-xs text-[#6B6B6B] font-mono mt-0.5">{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB</div>
                        </td>

                        <td class="px-6 py-4 text-sm text-[#6B6B6B] max-w-[180px]">
                            <span class="line-clamp-2 leading-snug">{{ $activity->location }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Selesai
                                </span>
                            @elseif($activity->activity_date->isToday())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Hari Ini
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[0.68rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Direncana
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-3">
                                <a href="{{ route('admin.activities.show', $activity->id) }}"
                                   class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">Detail</a>

                                @if(!$isPast)
                                    <span class="text-[#E8E4DF]">|</span>
                                    <button type="button"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $activity->id }}"
                                            data-title="{{ e($activity->title) }}"
                                            data-date="{{ $activity->activity_date->toDateString() }}"
                                            data-time="{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}"
                                            data-location="{{ e($activity->location) }}"
                                            data-description="{{ e($activity->description ?? '') }}"
                                            data-status="{{ $activity->status }}"
                                            class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150 cursor-pointer"
                                            style="background:none;border:none;padding:0;">
                                        Ubah
                                    </button>

                                    @if(!$hasDocuments)
                                        <span class="text-[#E8E4DF]">|</span>
                                        <button type="button"
                                                onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                                class="text-xs font-mono font-semibold text-red-500 hover:text-red-700 transition-colors duration-150 cursor-pointer"
                                                style="background:none;border:none;padding:0;">
                                            Hapus
                                        </button>
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

{{-- ================================================================
     CARDS — Mobile
     ================================================================ --}}
<div class="space-y-3 mb-4 md:hidden">
    @if($activities->isEmpty())
        <div class="card-serif p-10 text-center bg-[#FFFFFF]">
            <p class="font-serif text-lg text-[#1A1A1A] mb-1">
                @if(request()->anyFilled(['search', 'status'])) Tidak ada yang cocok @else Belum ada kegiatan @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-4">
                @if(request()->anyFilled(['search', 'status'])) Coba ubah kata kunci atau hapus filter. @else Tambahkan kegiatan pertama. @endif
            </p>
            @if(!request()->anyFilled(['search', 'status']))
                <button onclick="openModal('create-modal')" class="btn-primary">+ Tambah Kegiatan</button>
            @endif
        </div>
    @else
        @foreach($activities as $activity)
            @php $isPast = $activity->activity_date->lt(today()); $hasDocuments = $activity->documents_count > 0; @endphp
            <div class="card-serif p-5 bg-[#FFFFFF]">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <a href="{{ route('admin.activities.show', $activity->id) }}"
                       class="font-serif text-base text-[#1A1A1A] font-semibold hover:text-[#B8860B] transition-colors leading-snug flex-1">
                        {{ $activity->title }}
                    </a>
                    @if($activity->status === 'completed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-gray-100 text-gray-500 border border-gray-200 flex-shrink-0">Selesai</span>
                    @elseif($activity->activity_date->isToday())
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200 flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Hari Ini
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">Direncana</span>
                    @endif
                </div>
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
                <div class="flex items-center gap-3 pt-3 border-t border-[#F5F3F0]">
                    <a href="{{ route('admin.activities.show', $activity->id) }}"
                       class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors">Detail</a>
                    @if(!$isPast)
                        <span class="text-[#E8E4DF]">|</span>
                        <button type="button"
                                onclick="openEditModal(this)"
                                data-id="{{ $activity->id }}"
                                data-title="{{ e($activity->title) }}"
                                data-date="{{ $activity->activity_date->toDateString() }}"
                                data-time="{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}"
                                data-location="{{ e($activity->location) }}"
                                data-description="{{ e($activity->description ?? '') }}"
                                data-status="{{ $activity->status }}"
                                class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors cursor-pointer"
                                style="background:none;border:none;padding:0;">Ubah</button>
                        @if(!$hasDocuments)
                            <span class="text-[#E8E4DF]">|</span>
                            <button type="button"
                                    onclick="openDeleteModal({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                                    class="text-xs font-mono font-semibold text-red-500 hover:text-red-700 transition-colors cursor-pointer"
                                    style="background:none;border:none;padding:0;">Hapus</button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Pagination --}}
@if($activities->hasPages())
    <div class="mt-4">{{ $activities->links() }}</div>
@endif
