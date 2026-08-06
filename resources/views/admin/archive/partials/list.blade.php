@php
    $activeFilters = [];

    if (request('type') === 'surat')         $activeFilters[] = 'Jenis: Surat';
    if (request('type') === 'notulen')       $activeFilters[] = 'Jenis: Notulen';
    if (request('type') === 'dokumentasi')   $activeFilters[] = 'Jenis: Dokumentasi';

    if (request('file_format')) {
        $activeFilters[] = 'Format: ' . strtoupper(request('file_format'));
    }

    if (request('date_from') && request('date_to')) {
        if (request('date_from') === request('date_to')) {
            $activeFilters[] = 'Unggah: ' . \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y');
        } else {
            $activeFilters[] = 'Unggah: ' . \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') . ' s.d. ' . \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y');
        }
    }

    if (request('activity_date_from') && request('activity_date_to')) {
        if (request('activity_date_from') === request('activity_date_to')) {
            $activeFilters[] = 'Tgl Kegiatan: ' . \Carbon\Carbon::parse(request('activity_date_from'))->format('d/m/Y');
        } else {
            $activeFilters[] = 'Tgl Kegiatan: ' . \Carbon\Carbon::parse(request('activity_date_from'))->format('d/m/Y') . ' s.d. ' . \Carbon\Carbon::parse(request('activity_date_to'))->format('d/m/Y');
        }
    }

    if (request('sort') === 'oldest')        $activeFilters[] = 'Urutan: Terlama Diunggah';
    if (request('sort') === 'az')            $activeFilters[] = 'Urutan: Abjad A-Z';
    if (request('sort') === 'activity_date') $activeFilters[] = 'Urutan: Tgl Kegiatan';
@endphp

{{-- Result Summary --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 px-1" role="status" aria-live="polite">
    <p class="text-xs font-mono text-[#6B6B6B]">
        @if($documents->total() === 0)
            Tidak ada berkas ditemukan
        @else
            Menampilkan
            <span class="font-semibold text-[#1A1A1A]">{{ $documents->firstItem() }} s.d. {{ $documents->lastItem() }}</span>
            dari
            <span class="font-semibold text-[#1A1A1A]">{{ $documents->total() }}</span> berkas
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


{{-- ════════════════════════════════════════════════════════════
     TABLE — Desktop
     ════════════════════════════════════════════════════════════ --}}
<div class="card-serif bg-[#FFFFFF] overflow-hidden mb-4 hidden md:block">
    @if($documents->isEmpty())
        {{-- Empty State — Desktop --}}
        <div class="py-20 text-center px-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#F5F3F0] mb-5">
                <svg class="w-8 h-8 text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <p class="font-serif text-xl text-[#1A1A1A] mb-2">
                @if(request()->anyFilled(['search', 'type'])) Tidak ada dokumen yang cocok @else Belum ada dokumen @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-6 max-w-xs mx-auto leading-relaxed">
                @if(request()->anyFilled(['search', 'type']))
                    Coba ubah kata kunci pencarian atau hapus filter yang sedang aktif.
                @else
                    Belum ada dokumen arsip yang tersedia dalam sistem.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'type']))
                <a href="{{ route('admin.archive.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Tampilkan semua dokumen
                </a>
            @endif
        </div>
    @else
        <table class="w-full text-left border-collapse" role="table" aria-label="Daftar arsip dokumen">
            <thead>
                <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium w-12"></th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Nama Berkas</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Jenis</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Kegiatan</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium whitespace-nowrap">Tanggal Unggah</th>
                    <th scope="col" class="px-6 py-3.5 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E8E4DF]">
                @foreach($documents as $doc)
                    @php
                        $ext = strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                        if (strlen($ext) > 4 || !$ext) $ext = 'FILE';
                        $updateUrl = route('admin.documents.update', $doc);
                    @endphp
                    <tr class="hover:bg-[#F5F3F0]/50 transition-colors duration-150" role="row">
                        <td class="px-6 py-4">
                            <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-[#F5F3F0] border border-[#E8E4DF] flex items-center justify-center font-mono text-[0.62rem] font-bold text-[#B8860B] uppercase tracking-wider leading-none"
                                 aria-label="Format berkas: {{ $ext }}">
                                {{ $ext }}
                            </div>
                        </td>
                        <td class="px-6 py-4" style="max-width: 220px; min-width: 0;">
                            <p class="text-sm font-semibold text-[#1A1A1A] truncate leading-snug" title="{{ $doc->file_name }}">
                                {{ $doc->file_name }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-mono font-semibold uppercase tracking-wider text-[#B8860B] bg-[#B8860B]/10"
                                  aria-label="Jenis: {{ $doc->document_type }}">
                                {{ $doc->document_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4" style="max-width: 200px; min-width: 0;">
                            <a href="{{ route('admin.activities.show', $doc->activity) }}"
                               class="text-sm text-[#1A1A1A] hover:text-[#B8860B] transition-colors duration-150 line-clamp-1 block leading-snug"
                               title="{{ $doc->activity->title }}"
                               aria-label="Kegiatan: {{ $doc->activity->title }}">
                                {{ $doc->activity->title }}
                            </a>
                            <div class="text-[0.65rem] font-mono text-[#6B6B6B] mt-0.5">
                                {{ $doc->activity->activity_date->isoFormat('D MMM YYYY') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#6B6B6B] whitespace-nowrap">
                            <div class="font-medium text-[#1A1A1A]">{{ $doc->created_at->isoFormat('D MMM YYYY') }}</div>
                            <div class="text-xs font-mono mt-0.5">{{ $doc->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-3">
                                <button type="button"
                                        onclick="openEditDocumentModal('{{ $doc->id }}', '{{ $doc->document_type }}', '{{ e(addslashes($doc->file_name)) }}', '{{ $updateUrl }}')"
                                        class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150 cursor-pointer"
                                        style="background:none; border:none; padding:0;"
                                        aria-label="Edit dokumen {{ $doc->file_name }}">
                                    Edit
                                </button>
                                <span class="text-[#E8E4DF]" aria-hidden="true">|</span>
                                <a href="{{ route('admin.documents.show', $doc) }}"
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                                   aria-label="Buka dokumen {{ $doc->file_name }} di tab baru">
                                    Buka
                                </a>
                                <span class="text-[#E8E4DF]" aria-hidden="true">|</span>
                                <a href="{{ route('admin.documents.download', $doc) }}"
                                   onclick="downloadWithLoading(this, event)"
                                   class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                                   aria-label="Unduh dokumen {{ $doc->file_name }}">
                                    Unduh
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- ════════════════════════════════════════════════════════════
     CARDS — Mobile
     ════════════════════════════════════════════════════════════ --}}
<div class="space-y-3 mb-4 md:hidden">
    @if($documents->isEmpty())
        {{-- Empty State — Mobile --}}
        <div class="card-serif p-10 text-center bg-[#FFFFFF]">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-[#F5F3F0] mb-4">
                <svg class="w-7 h-7 text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
            </div>
            <p class="font-serif text-lg text-[#1A1A1A] mb-2">
                @if(request()->anyFilled(['search', 'type'])) Tidak ada yang cocok @else Belum ada dokumen @endif
            </p>
            <p class="text-sm text-[#6B6B6B] mb-5 leading-relaxed">
                @if(request()->anyFilled(['search', 'type']))
                    Coba ubah kata kunci atau hapus filter.
                @else
                    Belum ada dokumen arsip yang tersedia.
                @endif
            </p>
            @if(request()->anyFilled(['search', 'type']))
                <a href="{{ route('admin.archive.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Tampilkan semua dokumen
                </a>
            @endif
        </div>
    @else
        @foreach($documents as $doc)
            @php
                $ext = strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                if (strlen($ext) > 4 || !$ext) $ext = 'FILE';
                $updateUrl = route('admin.documents.update', $doc);
            @endphp
            <div class="card-serif p-5 bg-[#FFFFFF] hover:shadow-sm transition-shadow duration-150">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-11 h-11 flex-shrink-0 rounded-lg bg-[#F5F3F0] border border-[#E8E4DF] flex items-center justify-center font-mono text-[0.62rem] font-bold text-[#B8860B] uppercase tracking-wider leading-none"
                         aria-label="Format: {{ $ext }}">
                        {{ $ext }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-[#1A1A1A] truncate leading-snug" title="{{ $doc->file_name }}">
                            {{ $doc->file_name }}
                        </p>
                        <span class="inline-flex items-center mt-1 px-1.5 py-0.5 rounded text-[0.6rem] font-mono font-semibold uppercase tracking-wider text-[#B8860B] bg-[#B8860B]/10"
                              aria-label="Jenis: {{ $doc->document_type }}">
                            {{ $doc->document_type }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4 text-xs">
                    <div>
                        <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Kegiatan</span>
                        <a href="{{ route('admin.activities.show', $doc->activity) }}"
                           class="block text-[#1A1A1A] hover:text-[#B8860B] transition-colors font-medium mt-0.5 line-clamp-2 leading-snug"
                           aria-label="Kegiatan: {{ $doc->activity->title }}">
                            {{ $doc->activity->title }}
                        </a>
                    </div>
                    <div>
                        <span class="font-mono uppercase tracking-wider text-[#6B6B6B] text-[0.62rem]">Tanggal Unggah</span>
                        <div class="text-[#1A1A1A] font-medium mt-0.5 leading-snug">
                            {{ $doc->created_at->isoFormat('D MMM YYYY') }}
                        </div>
                        <div class="text-[#6B6B6B] font-mono text-[0.62rem]">
                            {{ $doc->created_at->format('H:i') }} WIB
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-3 border-t border-[#F5F3F0]">
                    <button type="button"
                            onclick="openEditDocumentModal('{{ $doc->id }}', '{{ $doc->document_type }}', '{{ e(addslashes($doc->file_name)) }}', '{{ $updateUrl }}')"
                            class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150 cursor-pointer"
                            style="background:none; border:none; padding:0;"
                            aria-label="Edit dokumen {{ $doc->file_name }}">
                        Edit
                    </button>
                    <span class="text-[#E8E4DF]" aria-hidden="true">|</span>
                    <a href="{{ route('admin.documents.show', $doc) }}"
                       target="_blank" rel="noopener noreferrer"
                       class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                       aria-label="Buka dokumen {{ $doc->file_name }} di tab baru">
                        Buka
                    </a>
                    <span class="text-[#E8E4DF]" aria-hidden="true">|</span>
                    <a href="{{ route('admin.documents.download', $doc) }}"
                       onclick="downloadWithLoading(this, event)"
                       class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                       aria-label="Unduh dokumen {{ $doc->file_name }}">
                        Unduh
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</div>

{{-- Pagination --}}
@if($documents->hasPages())
    <div class="mt-4" role="navigation" aria-label="Navigasi halaman arsip">
        {{ $documents->links() }}
    </div>
@endif
