<x-layouts.admin title="Detail Kegiatan">

    @push('styles')
    <style>
        .back-btn-danger:hover {
            color: #DC2626 !important;
            background: rgba(220, 38, 38, 0.06) !important;
            border-color: rgba(220, 38, 38, 0.3) !important;
        }
        .back-btn-danger:hover .back-btn-text {
            color: #DC2626 !important;
            cursor: pointer;
        }
    </style>
    @endpush

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Rincian Kegiatan</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="font-serif text-3xl md:text-4xl text-[#1A1A1A] tracking-tight leading-tight">
                    {{ $activity->title }}
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.activities.index') }}"
                   onclick="__navGo(this, this.href); return false;"
                   class="back-btn" title="Kembali ke Daftar Kegiatan">
                    <svg class="back-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span class="back-btn-text">Kembali</span>
                </a>
                @if(!$activity->is_started)
                    <button type="button" onclick="openEditActivityModalShow()" class="btn-primary">
                        Ubah Kegiatan
                    </button>
                    <button type="button"
                            onclick="openDeleteModalShow({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                            class="back-btn back-btn-danger"
                            title="Hapus Kegiatan"
                            style="cursor: pointer;">
                        <svg class="back-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                        <span class="back-btn-text">Hapus</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Grid 2/3 — 1/3 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ══ KIRI (2/3) — Informasi Kegiatan ══ --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Detail Card --}}
            <div class="card-serif bg-[#FFFFFF]">

                {{-- Header Kartu: Info Pembuat --}}
                <div class="px-8 py-4 border-b border-[#F5F3F0] flex flex-wrap items-center justify-between gap-x-6 gap-y-1 hover:bg-[#FAFAF8] transition-colors duration-150">
                    <span class="text-xs text-[#6B6B6B] font-mono">
                        Dibuat oleh: <span class="text-[#1A1A1A]">{{ $activity->user->name ?? '-' }}</span>
                    </span>
                    <span class="text-xs text-[#6B6B6B] font-mono">
                        <time datetime="{{ $activity->created_at->format('Y-m-d\TH:i') }}">
                            Dibuat: <span class="text-[#1A1A1A]">{{ $activity->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB</span>
                        </time>
                    </span>
                </div>

                <div class="p-8 space-y-6">

                    {{-- Meta: Waktu · Tempat · Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pb-7 border-b border-[#F5F3F0]">

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Waktu Pelaksanaan</span>
                            <div class="text-[#1A1A1A] font-medium text-base leading-snug">
                                {{ $activity->activity_date->isoFormat('dddd, D MMMM YYYY') }}
                            </div>
                            <div class="text-[#6B6B6B] text-xs font-mono mt-1">
                                <time datetime="{{ $activity->activity_date->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}">
                                    Pukul {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                                </time>
                            </div>
                        </div>

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Tempat / Ruangan</span>
                            <div class="text-[#1A1A1A] font-medium text-base leading-snug">{{ $activity->location }}</div>
                            <div class="text-[#6B6B6B] text-xs mt-1 leading-relaxed">Bidang APTIKA, Diskominfotik Riau</div>
                        </div>

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Status</span>
                            @if($activity->computed_status === 'Selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200"
                                      role="status" aria-label="Status kegiatan: Selesai">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400" aria-hidden="true"></span>Selesai
                                </span>
                            @elseif($activity->computed_status === 'Sudah Berlangsung')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200"
                                      role="status" aria-label="Status kegiatan: Sudah Berlangsung">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>Sudah Berlangsung
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200"
                                      role="status" aria-label="Status kegiatan: Direncana">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500" aria-hidden="true"></span>Direncana
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- Deskripsi --}}
                    <div class="p-4 -m-4 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                        <span class="small-caps text-[0.65rem] block mb-2">Deskripsi &amp; Agenda</span>
                        <div class="text-sm text-[#1A1A1A] leading-relaxed whitespace-pre-line"
                             style="font-family: 'Source Sans 3', system-ui, sans-serif;">
                            {!! $activity->description
                                ? e($activity->description)
                                : '<em class="text-[#6B6B6B]">Tidak ada deskripsi kegiatan.</em>' !!}
                        </div>
                    </div>

                </div>{{-- /p-8 --}}

            </div>{{-- /card-serif --}}

        </div>

        {{-- ══ KANAN (1/3) — Dokumen & Lampiran ══ --}}
        <div class="space-y-5">

            <div class="card-serif bg-[#FFFFFF] overflow-hidden">

                {{-- Header: small-caps label + count (border #E8E4DF) --}}
                <div class="px-5 py-4 border-b border-[#E8E4DF] flex items-center justify-between">
                    <span class="small-caps text-[0.65rem]">Dokumen &amp; Lampiran</span>
                    <span class="text-[0.65rem] font-mono text-[#6B6B6B]">{{ $documents->count() }} berkas</span>
                </div>

                {{-- Filter tab jenis dokumen (px-5 seimbang dengan tepi kartu, 1 baris tanpa scrollbar) --}}
                <div class="px-5 py-3.5 border-b border-[#E8E4DF] bg-[#FAFAF8] flex items-center justify-between gap-1 text-[0.62rem] font-mono flex-nowrap overflow-hidden">
                    <a href="{{ route('admin.activities.show', $activity) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ !request('type') ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}">
                        Semua <span class="opacity-70">({{ $documentCounts['all'] }})</span>
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'surat']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'surat' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}">
                        Surat <span class="opacity-70">({{ $documentCounts['surat'] }})</span>
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'notulen']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'notulen' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}">
                        Notulen <span class="opacity-70">({{ $documentCounts['notulen'] }})</span>
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'dokumentasi']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'dokumentasi' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}">
                        Dokumentasi <span class="opacity-70">({{ $documentCounts['dokumentasi'] }})</span>
                    </a>
                </div>

                {{-- Daftar dokumen (border pembatas #E8E4DF, scrollable max 3 list jika > 3 dokumen) --}}
                <div class="divide-y divide-[#E8E4DF]" style="{{ $documents->count() > 3 ? 'max-height: 204px; overflow-y: auto;' : '' }}">
                    @if($documentCounts['all'] === 0)
                        <div class="py-16 my-4 flex flex-col items-center gap-3 text-center px-6">
                            <svg class="w-8 h-8 text-[#E8E4DF]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-mono text-[#6B6B6B]">
                                    {{ request('type') ? 'Tidak ada dokumen untuk jenis ini' : 'Belum ada dokumen' }}
                                </p>
                                <p class="text-[0.65rem] text-[#9A948D] font-mono mt-1">
                                    {{ $activity->activity_date->gt(today()) ? 'Dokumen dapat diunggah setelah kegiatan berlangsung' : 'Klik tombol di bawah untuk mengunggah' }}
                                </p>
                            </div>
                        </div>
                    @else
                        @foreach($documents as $doc)
                            @php
                                $ext = strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                                if (strlen($ext) > 4 || !$ext) $ext = 'FILE';
                            @endphp
                            {{-- Baris list file proporsional & seragam --}}
                            <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-[#FAFAF8] transition-colors duration-150 group">

                                {{-- Extension badge (proporsional w-10 h-10) --}}
                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-[#F5F3F0] border border-[#E8E4DF] flex items-center justify-center font-mono text-[0.62rem] font-bold text-[#B8860B] uppercase tracking-wider leading-none">
                                    {{ $ext }}
                                </div>

                                {{-- Info berkas --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-[#1A1A1A] truncate leading-snug" title="{{ $doc->file_name }}">
                                        {{ $doc->file_name }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.58rem] font-mono font-semibold uppercase tracking-wider text-[#B8860B] bg-[#B8860B]/10">
                                            {{ $doc->document_type }}
                                        </span>
                                        <span class="text-[#D4C4A0] text-[0.60rem]">·</span>
                                        <span class="text-[0.62rem] font-mono text-[#6B6B6B]">{{ $doc->created_at->isoFormat('D MMM YYYY') }}</span>
                                    </div>
                                </div>

                                {{-- Aksi: Ikon SVG --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    {{-- Edit --}}
                                    <button type="button"
                                            onclick="openEditDocumentModal({{ $doc->id }}, '{{ $doc->document_type }}', '{{ addslashes($doc->file_name) }}', '{{ route('admin.documents.update', $doc) }}')"
                                            class="w-7 h-7 flex items-center justify-center rounded text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF] transition-colors duration-150"
                                            title="Ubah Dokumen"
                                            style="background:none; border:none; cursor:pointer;">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </button>
                                    {{-- Buka --}}
                                    <a href="{{ route('admin.documents.show', $doc) }}"
                                       target="_blank" rel="noopener noreferrer"
                                       class="w-7 h-7 flex items-center justify-center rounded text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF] transition-colors duration-150"
                                       title="Buka Dokumen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                        </svg>
                                    </a>
                                    {{-- Unduh --}}
                                    <a href="{{ route('admin.documents.download', $doc) }}"
                                       onclick="downloadWithLoading(this, event)"
                                       class="w-7 h-7 flex items-center justify-center rounded text-[#6B6B6B] hover:text-[#1A1A1A] hover:bg-[#E8E4DF] transition-colors duration-150"
                                       title="Unduh Dokumen">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Tombol Unggah Dokumen --}}
                @if($activity->activity_date->gt(today()))
                    {{-- Non-Aktif jika Kegiatan Belum Berlangsung --}}
                    <button type="button"
                            disabled
                            title="Dokumen belum dapat diunggah karena kegiatan belum berlangsung"
                            class="w-full justify-center gap-2 border-t border-[#E8E4DF] bg-[#FAFAF8] text-[#999999] font-semibold py-3.5 px-4 flex items-center cursor-not-allowed text-xs font-mono select-none"
                            style="border-top-left-radius: 0 !important; border-top-right-radius: 0 !important; border-bottom-left-radius: 7px !important; border-bottom-right-radius: 7px !important;">
                        <svg class="w-4 h-4 flex-shrink-0 text-[#999999]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 12.75v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                        Unggah Dokumen (Belum Berlangsung)
                    </button>
                @else
                    <button type="button"
                            onclick="openUploadDocumentModal()"
                            class="btn-primary w-full justify-center gap-2 border-t border-[#E8E4DF]"
                            style="border-top-left-radius: 0 !important; border-top-right-radius: 0 !important; border-bottom-left-radius: 7px !important; border-bottom-right-radius: 7px !important;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Unggah Dokumen
                    </button>
                @endif
            </div>

        </div>

    </div>







    {{-- ── Modals ── --}}
    @push('modals')
    {{-- Delete Activity Modal --}}
    <div id="show-delete-modal" class="modal-backdrop hidden"
         onclick="if(event.target===event.currentTarget) closeDeleteModalShow()">
        <div class="modal-box">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Hapus Kegiatan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Konfirmasi penghapusan data kegiatan dari sistem.</p>
                </div>
                <button type="button" onclick="closeDeleteModalShow()"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form id="show-delete-form" method="POST" action="{{ route('admin.activities.destroy', $activity->id) }}">
                @csrf
                @method('DELETE')

                <div class="px-8 py-6 space-y-4">
                    <p class="text-sm text-[#1A1A1A] leading-relaxed mb-3">
                        Apakah Anda yakin ingin menghapus kegiatan <strong id="show-delete-name" class="font-semibold text-[#1A1A1A]"></strong>?
                    </p>
                    <div class="p-3.5 mt-5 rounded-lg bg-red-50 border border-red-200 text-xs text-red-700 leading-relaxed font-mono">
                        ⚠️ Tindakan ini tidak dapat dibatalkan. Seluruh berkas dokumen terlampir pada kegiatan ini akan terhapus.
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-show-delete-submit" type="submit" class="btn-primary bg-[#DC2626] hover:bg-[#B91C1C] border-[#DC2626]">
                        Hapus Kegiatan
                    </button>
                    <button type="button" onclick="closeDeleteModalShow()" class="btn-secondary">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Activity Modal --}}
    <div id="edit-activity-modal-show" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget && this.dataset.locked !== '1') closeEditActivityModalShow()">
        <div class="modal-box">
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Ubah Kegiatan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
                </div>
                <button type="button" onclick="closeEditActivityModalShow()"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="edit-activity-form-show" method="POST" action="{{ route('admin.activities.update', $activity->id) }}" novalidate class="flex flex-col overflow-hidden min-h-0 flex-1">
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-5 modal-form-body">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <label for="show-e-title" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input id="show-e-title" type="text" name="title"
                                   value="{{ old('title', $activity->title) }}"
                                   maxlength="255" placeholder="Contoh: Rapat Evaluasi Smart City Semester I"
                                   class="input-serif @error('title') border-red-400 bg-red-50 @enderror"
                                   autocomplete="off" required>
                            @error('title')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="show-e-date" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tanggal <span class="text-red-500">*</span></label>
                                <input id="show-e-date" type="date" name="activity_date"
                                       value="{{ old('activity_date', $activity->activity_date->toDateString()) }}"
                                       class="input-serif @error('activity_date') border-red-400 bg-red-50 @enderror" required>
                                @error('activity_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="show-e-time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Waktu <span class="text-red-500">*</span></label>
                                <input id="show-e-time" type="time" name="time"
                                       value="{{ old('time', \Carbon\Carbon::parse($activity->time)->format('H:i')) }}"
                                       class="input-serif @error('time') border-red-400 bg-red-50 @enderror" required>
                                @error('time')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="show-e-location" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tempat <span class="text-red-500">*</span></label>
                            <input id="show-e-location" type="text" name="location"
                                   value="{{ old('location', $activity->location) }}"
                                   maxlength="255" placeholder="Contoh: Ruang Rapat Bidang APTIKA Lt. 3"
                                   class="input-serif @error('location') border-red-400 bg-red-50 @enderror"
                                   autocomplete="off" required>
                            @error('location')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="show-e-description" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                                Deskripsi <span class="text-xs font-normal text-[#6B6B6B]">(opsional)</span>
                            </label>
                            <textarea id="show-e-description" name="description" rows="4" maxlength="2000"
                                      placeholder="Agenda, catatan, atau detail kegiatan..."
                                      class="input-serif @error('description') border-red-400 bg-red-50 @enderror"
                                      style="height: auto; padding-top: 0.75rem; padding-bottom: 0.75rem;">{{ old('description', $activity->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-show-edit-submit" type="submit" class="btn-primary">Perbarui Kegiatan</button>
                    <button type="button" onclick="closeEditActivityModalShow()" class="btn-secondary">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Document Modal --}}
    <div id="edit-document-modal" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget && this.dataset.locked !== '1') closeEditDocumentModal()">
        <div class="modal-box">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="font-serif text-2xl text-[#1A1A1A]">Edit Dokumen Arsip</h2>
                        <span id="edit-doc-type-badge" class="inline-flex items-center px-2.5 py-0.5 rounded text-[0.65rem] font-mono font-semibold uppercase tracking-wider text-[#B8860B] bg-[#B8860B]/10 border border-[#B8860B]/20"></span>
                    </div>
                    <p class="text-xs text-[#6B6B6B] mt-1">Unggah berkas baru untuk menggantikan berkas dokumen saat ini.</p>
                </div>
                <button type="button" onclick="closeEditDocumentModal()"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form id="edit-document-form" method="POST" action="" enctype="multipart/form-data" novalidate class="flex flex-col overflow-hidden min-h-0 flex-1">
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-5 modal-form-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_document_type" name="document_type">

                    <div class="space-y-5">
                        {{-- ── 1. BERKAS LAMA / SAAT INI ── --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[0.68rem] font-semibold uppercase tracking-wider text-[#6B6B6B] font-mono">
                                    Berkas Saat Ini (Lama)
                                </span>
                                <span class="text-[0.62rem] font-mono text-[#6B6B6B] bg-[#F5F3F0] px-2 py-0.5 rounded border border-[#E8E4DF]">
                                    Tersimpan
                                </span>
                            </div>

                            <div class="p-3.5 border border-[#E8E4DF] rounded-xl bg-[#FAFAF8] flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div id="edit-file-ext" class="w-9 h-9 rounded bg-[#FFFFFF] border border-[#E8E4DF] text-[#B8860B] font-mono text-[0.65rem] font-bold flex items-center justify-center flex-shrink-0 uppercase shadow-xs">
                                        FILE
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p id="edit-current-filename" class="text-xs font-semibold text-[#1A1A1A] font-mono truncate"></p>
                                        <p class="text-[0.65rem] text-[#6B6B6B] font-mono mt-0.5">Dokumen aktif terdaftar saat ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ── PEMISAH / DIVIDER DIGANTIKAN DENGAN ── --}}
                        <div class="flex items-center gap-3 py-0.5">
                            <div class="h-px flex-1 bg-[#E8E4DF]"></div>
                            <span class="inline-flex items-center gap-1.5 text-[0.65rem] font-mono text-[#6B6B6B] bg-[#FFFFFF] px-3 py-1 rounded-full border border-[#E8E4DF]">
                                <svg class="w-3.5 h-3.5 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                                </svg>
                                Digantikan dengan
                            </span>
                            <div class="h-px flex-1 bg-[#E8E4DF]"></div>
                        </div>

                        {{-- ── 2. BERKAS BARU / PENGGANTI ── --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[0.68rem] font-semibold uppercase tracking-wider text-[#B8860B] font-mono">
                                    Berkas Baru (Pengganti)
                                </span>
                                <span id="new-file-status-badge" class="text-[0.62rem] font-mono px-2 py-0.5 rounded border bg-gray-50 text-gray-500 border-gray-200">
                                    Belum ada berkas
                                </span>
                            </div>

                            {{-- Drop Zone Berkas Baru (Tampil saat 0 berkas) --}}
                            <div id="edit-drop-zone"
                                 class="flex flex-col items-center justify-center p-5 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 text-center border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF]"
                                 onclick="document.getElementById('edit_upload_file').click()">

                                <div class="w-10 h-10 rounded-xl bg-[#F5F3F0] flex items-center justify-center mb-2 text-[#B8860B]">
                                    <svg id="edit-drop-icon" class="w-5 h-5 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                                    </svg>
                                </div>

                                <p class="text-xs text-[#1A1A1A] font-medium">
                                    Seret & lepas berkas baru di sini, atau <span class="text-[#B8860B] font-semibold underline underline-offset-2 hover:text-[#9A7009]">Pilih Berkas</span>
                                </p>
                                <p class="text-[0.65rem] text-[#6B6B6B] mt-1 font-mono">
                                    PDF, Word, JPG, PNG, atau MP4 (Maks. 10MB)
                                </p>
                            </div>

                            <input id="edit_upload_file" name="file" type="file"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                                   class="sr-only"
                                   onchange="if(this.files && this.files[0]) applyEditFile(this.files[0]);">

                            {{-- Card Berkas Baru Terpilih (Tampil hanya saat berkas baru dipilih) --}}
                            <div id="edit-new-file-card" class="hidden p-3.5 border border-amber-300 rounded-xl bg-amber-50/20 flex items-center justify-between gap-3 transition-all">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div id="edit-new-file-ext" class="w-9 h-9 rounded bg-[#B8860B]/10 border border-[#B8860B]/30 text-[#B8860B] font-mono text-[0.65rem] font-bold flex items-center justify-center flex-shrink-0 uppercase">
                                        NEW
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p id="edit-new-filename" class="text-xs font-semibold text-[#1A1A1A] font-mono truncate"></p>
                                        <p id="edit-new-filesize" class="text-[0.65rem] text-[#B8860B] font-mono mt-0.5 font-medium"></p>
                                    </div>
                                </div>
                                {{-- Tombol Batal Ikon "X" --}}
                                <button type="button" onclick="cancelNewFileSelection()"
                                        class="text-[#6B6B6B] hover:text-red-600 hover:bg-red-50 transition-colors p-1.5 rounded-lg flex-shrink-0"
                                        title="Batal pilih berkas baru">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-submit-edit-doc" type="submit" class="btn-primary flex-1 justify-center" disabled>
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeEditDocumentModal()" class="btn-secondary flex-1 justify-center">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Upload Document Modal --}}
    <div id="upload-document-modal" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget && !isUploadingActive) closeUploadDocumentModal()">
        <div class="modal-box" style="max-width:68rem; width:95vw; height:85vh; max-height:calc(100vh - 4rem);">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 id="upload-modal-title" class="font-serif text-2xl text-[#1A1A1A]">Unggah Dokumen</h2>
                    <p id="upload-modal-subtitle" class="text-xs text-[#6B6B6B] mt-0.5">Pilih berkas untuk jenis Surat, Notulen, atau Dokumentasi.</p>
                </div>
                <button type="button" onclick="closeUploadDocumentModal()" id="btn-close-upload-modal"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ══ PHASE 1: SELECTION VIEW ══ --}}
            <div id="upload-phase-select" class="flex flex-col overflow-hidden min-h-0 flex-1">
                {{-- 2-column body: dropzones left | queue right --}}
                <div class="flex-1 flex overflow-hidden min-h-0">

                    {{-- LEFT: 3 stacked square dropzones --}}
                    <div class="flex flex-col gap-3 px-5 py-5 border-r border-[#E8E4DF] overflow-y-auto flex-shrink-0" style="width: 220px;">

                        {{-- 1. Surat --}}
                        <div class="flex flex-col flex-1 min-h-0">
                            <div class="flex items-center justify-between mb-1.5 flex-shrink-0">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide truncate">Surat / Dokumen</span>
                                <span id="count-badge-surat" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-surat" class="relative flex-1 flex flex-col items-center justify-center p-3 border-2 border-dashed rounded-xl transition-all duration-200 border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] group overflow-hidden" style="cursor:pointer;">
                                <div class="w-8 h-8 rounded-lg bg-[#F5F3F0] flex items-center justify-center mb-1.5 text-[#B8860B] group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <p class="text-[0.72rem] text-[#1A1A1A] font-medium leading-snug text-center"><span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Berkas</span><br><span class="text-[0.65rem] text-[#6B6B6B]">atau seret ke sini</span></p>
                                <p class="text-[0.58rem] text-[#9A948D] mt-1 font-mono text-center">PDF, Word, JPG (10MB)</p>
                                <input id="input_file_surat" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('surat', this)">
                            </div>
                        </div>

                        {{-- 2. Notulen --}}
                        <div class="flex flex-col flex-1 min-h-0">
                            <div class="flex items-center justify-between mb-1.5 flex-shrink-0">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide truncate">Notulen</span>
                                <span id="count-badge-notulen" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-notulen" class="relative flex-1 flex flex-col items-center justify-center p-3 border-2 border-dashed rounded-xl transition-all duration-200 border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] group overflow-hidden" style="cursor:pointer;">
                                <div class="w-8 h-8 rounded-lg bg-[#F5F3F0] flex items-center justify-center mb-1.5 text-[#B8860B] group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                </div>
                                <p class="text-[0.72rem] text-[#1A1A1A] font-medium leading-snug text-center"><span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Berkas</span><br><span class="text-[0.65rem] text-[#6B6B6B]">atau seret ke sini</span></p>
                                <p class="text-[0.58rem] text-[#9A948D] mt-1 font-mono text-center">PDF, Word, JPG (10MB)</p>
                                <input id="input_file_notulen" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('notulen', this)">
                            </div>
                        </div>

                        {{-- 3. Dokumentasi --}}
                        <div class="flex flex-col flex-1 min-h-0">
                            <div class="flex items-center justify-between mb-1.5 flex-shrink-0">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide truncate">Dokumentasi</span>
                                <span id="count-badge-dokumentasi" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-dokumentasi" class="relative flex-1 flex flex-col items-center justify-center p-3 border-2 border-dashed rounded-xl transition-all duration-200 border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] group overflow-hidden" style="cursor:pointer;">
                                <div class="w-8 h-8 rounded-lg bg-[#F5F3F0] flex items-center justify-center mb-1.5 text-[#B8860B] group-hover:scale-105 transition-transform duration-200 flex-shrink-0">
                                    <svg class="w-4 h-4 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                                    </svg>
                                </div>
                                <p class="text-[0.72rem] text-[#1A1A1A] font-medium leading-snug text-center"><span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Berkas</span><br><span class="text-[0.65rem] text-[#6B6B6B]">atau seret ke sini</span></p>
                                <p class="text-[0.58rem] text-[#9A948D] mt-1 font-mono text-center">JPG, PNG, MP4 (10MB)</p>
                                <input id="input_file_dokumentasi" type="file" multiple accept=".jpg,.jpeg,.png,.mp4"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('dokumentasi', this)">
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT: Queue panel --}}
                    <div class="flex flex-col flex-1 overflow-hidden">
                        <div class="px-6 pt-5 pb-4 border-b border-[#E8E4DF] flex items-center justify-between flex-shrink-0">
                            <span class="text-xs font-semibold text-[#1A1A1A] font-serif uppercase tracking-wider">Daftar Berkas Siap Diunggah</span>
                            <span id="queue-total-badge" class="px-2.5 py-0.5 rounded-full bg-[#F5F3F0] text-[#B8860B] font-mono text-[0.68rem] font-medium border border-[#E8E4DF]">0 berkas terpilih</span>
                        </div>

                        <div class="flex-1 overflow-y-auto px-5 py-4">
                            <div id="queue-empty-state" class="h-full border border-dashed border-[#E8E4DF] rounded-xl text-center bg-[#FAFAF8] flex flex-col items-center justify-center" style="padding: 2.5rem 1.5rem; gap: 0.5rem; min-height: 200px;">
                                <svg class="text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 32px; height: 32px; flex-shrink: 0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                                <p class="text-xs font-medium text-[#6B6B6B]" style="margin: 0;">Belum ada berkas dipilih</p>
                                <p class="text-[0.68rem] text-[#9A948D]" style="margin: 0;">Pilih atau seret berkas di sebelah kiri.</p>
                            </div>

                            <div id="queue-file-list" class="hidden space-y-3">
                                {{-- Items dynamically rendered via JS --}}
                            </div>
                        </div>

                        {{-- Modal Footer inside right panel --}}
                        <div class="flex items-center gap-3 px-5 py-4 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                            <button id="btn-start-upload" type="button" onclick="startBatchUpload()" class="btn-primary flex-1 justify-center" style="min-height:2.75rem;" disabled>
                                Unggah Dokumen
                            </button>
                            <button type="button" onclick="closeUploadDocumentModal()" class="btn-secondary flex-1 justify-center" style="min-height:2.75rem;">
                                Batal
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ══ PHASE 2: PROGRESS VIEW ══ --}}
            <div id="upload-phase-progress" class="hidden flex flex-col overflow-hidden min-h-0 flex-1">
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-5 modal-form-body">

                    {{-- Status Banner --}}
                    <div id="upload-status-alert" class="p-4 rounded-xl border bg-amber-50 border-amber-200 text-amber-900 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <span class="btn-spinner"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p id="upload-status-title" class="text-xs font-semibold font-mono uppercase tracking-wider">Proses Mengunggah Dokumen</p>
                            <p id="upload-status-sub" class="text-xs text-amber-700 font-mono mt-0.5">Harap tunggu hingga seluruh berkas selesai dikirim ke storage...</p>
                        </div>
                    </div>

                    {{-- Overall Progress --}}
                    <div>
                        <div class="flex items-center justify-between text-xs font-mono text-[#6B6B6B] mb-1.5">
                            <span>Kemajuan Total</span>
                            <span id="overall-progress-percent">0%</span>
                        </div>
                        <div class="w-full h-2.5 rounded-full bg-[#E8E4DF] overflow-hidden">
                            <div id="overall-progress-bar" class="h-full bg-[#B8860B] transition-all duration-200 rounded-full" style="width:0%;"></div>
                        </div>
                    </div>

                    {{-- Progress Items List --}}
                    <div>
                        <span class="text-xs font-semibold text-[#1A1A1A] block mb-2">Rincian Berkas</span>
                        <div id="progress-file-list" class="space-y-2.5 max-h-60 overflow-y-auto pr-1">
                            {{-- Items dynamically rendered via JS with progress bars --}}
                        </div>
                    </div>

                </div>

                {{-- Progress Modal Footer --}}
                <div id="progress-footer-actions" class="hidden flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-cancel-upload-process" type="button" onclick="cancelUploadProcess()" class="btn-secondary flex-1 justify-center border-red-200 text-red-700 hover:bg-red-50 hidden" style="min-height:2.75rem;">
                        Batalkan Unggahan
                    </button>
                    <button id="btn-retry-upload" type="button" onclick="retryUpload()" class="btn-primary flex-1 justify-center hidden" style="min-height:2.75rem;">
                        Coba Lagi
                    </button>
                    <button id="btn-back-selection" type="button" onclick="backToSelectionPhase()" class="btn-secondary flex-1 justify-center hidden" style="min-height:2.75rem;">
                        Kembali ke Pemilihan
                    </button>
                </div>
            </div>

        </div>
    </div>

    @endpush

    {{-- ── Scripts ── --}}
    <script>
    var queuedUploadFiles = [];
    var isUploadingActive = false;

    let showEditInitialState = {};

    function storeShowEditInitialState() {
        showEditInitialState = {
            title: document.getElementById('show-e-title')?.value || '',
            date: document.getElementById('show-e-date')?.value || '',
            time: document.getElementById('show-e-time')?.value || '',
            location: document.getElementById('show-e-location')?.value || '',
            description: document.getElementById('show-e-description')?.value || ''
        };
        validateShowEditForm();
    }

    function validateShowEditForm() {
        const btn = document.getElementById('btn-show-edit-submit');
        if (!btn) return;
        const currentTitle = document.getElementById('show-e-title')?.value || '';
        const currentDate = document.getElementById('show-e-date')?.value || '';
        const currentTime = document.getElementById('show-e-time')?.value || '';
        const currentLocation = document.getElementById('show-e-location')?.value || '';
        const currentDescription = document.getElementById('show-e-description')?.value || '';

        const hasRequired = currentTitle.trim() !== '' && currentDate !== '' && currentTime !== '' && currentLocation.trim() !== '';
        const isChanged = currentTitle !== showEditInitialState.title ||
                          currentDate !== showEditInitialState.date ||
                          currentTime !== showEditInitialState.time ||
                          currentLocation !== showEditInitialState.location ||
                          currentDescription !== showEditInitialState.description;

        btn.disabled = !(hasRequired && isChanged);
        if (!hasRequired) {
            btn.title = 'Lengkapi seluruh kolom wajib (Judul, Tanggal, Waktu, Tempat) terlebih dahulu';
        } else if (!isChanged) {
            btn.title = 'Ubah setidaknya satu data kegiatan untuk memperbarui';
        } else {
            btn.title = 'Simpan perubahan rincian kegiatan';
        }
    }

    // ── Form Submit Loading Spinners (show page) ───────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        ['show-e-title', 'show-e-date', 'show-e-time', 'show-e-location', 'show-e-description'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', validateShowEditForm);
                el.addEventListener('change', validateShowEditForm);
            }
        });

        var deleteForm = document.getElementById('show-delete-form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function () {
                var btn = document.getElementById('btn-show-delete-submit');
                if (typeof setButtonLoading === 'function') {
                    setButtonLoading(btn, true);
                }
                var timer = armSubmitTimeout('show-delete-modal', function() {
                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }
        var editForm = document.getElementById('edit-activity-form-show');
        if (editForm) {
            editForm.addEventListener('submit', function () {
                var btn = document.getElementById('btn-show-edit-submit');
                lockModalForm('edit-activity-modal-show');
                if (typeof setButtonLoading === 'function') {
                    setButtonLoading(btn, true);
                }
                var timer = armSubmitTimeout('edit-activity-modal-show', function(id) {
                    unlockModalForm(id);
                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, false);
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }
        var editDocForm = document.getElementById('edit-document-form');
        var editDocBtn  = document.getElementById('btn-submit-edit-doc');
        if (editDocForm && editDocBtn) {
            editDocForm.addEventListener('submit', function () {
                lockModalForm('edit-document-modal');
                var rect = editDocBtn.getBoundingClientRect();
                editDocBtn.style.width  = rect.width  + 'px';
                editDocBtn.style.height = rect.height + 'px';
                editDocBtn.innerHTML = '<span style="display:inline-block;width:1em;height:1em;border:2px solid currentColor;border-top-color:transparent;border-radius:50%;animation:btnSpin 0.65s linear infinite;"></span>';
                var timer = armSubmitTimeout('edit-document-modal', function(id) {
                    unlockModalForm(id);
                    editDocBtn.style.width = '';
                    editDocBtn.style.height = '';
                    editDocBtn.innerHTML = 'Simpan Perubahan';
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }
    });

    // Upload Document Modal
    function openUploadDocumentModal() {
        closeEditDocumentModal();
        closeDeleteModalShow();
        backToSelectionPhase();
        document.getElementById('upload-document-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.add('modal-open-filter');
        var startUploadBtn = document.getElementById('btn-start-upload');
        if (startUploadBtn) {
            const isEmpty = (queuedUploadFiles.length === 0);
            startUploadBtn.disabled = isEmpty;
            startUploadBtn.title = isEmpty
                ? 'Pilih setidaknya 1 berkas dokumen di kolom sebelah kiri terlebih dahulu'
                : 'Unggah seluruh berkas terpilih';
        }
    }
    function closeUploadDocumentModal() {
        if (isUploadingActive) return;
        document.getElementById('upload-document-modal').classList.add('hidden');
        document.body.style.overflow = '';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.remove('modal-open-filter');
    }

    // Edit Activity Modal
    function openEditActivityModalShow() {
        closeUploadDocumentModal();
        closeEditDocumentModal();
        closeDeleteModalShow();
        document.getElementById('edit-activity-modal-show').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        storeShowEditInitialState();
    }
    function closeEditActivityModalShow() {
        // Jangan tutup jika mekanisme pengaman aktif
        var modal = document.getElementById('edit-activity-modal-show');
        if (modal && modal.dataset.locked === '1') return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Delete Activity Modal
    function openDeleteModalShow(id, title) {
        closeUploadDocumentModal();
        closeEditDocumentModal();
        document.getElementById('show-delete-name').textContent = '"' + title + '"';
        document.getElementById('show-delete-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.add('modal-open-filter');
    }
    function closeDeleteModalShow() {
        document.getElementById('show-delete-modal').classList.add('hidden');
        document.body.style.overflow = '';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.remove('modal-open-filter');
    }

    window.cancelNewFileSelection = function() {
        var editInput   = document.getElementById('edit_upload_file');
        var editBtn     = document.getElementById('btn-submit-edit-doc');
        var editZone    = document.getElementById('edit-drop-zone');
        var newCard     = document.getElementById('edit-new-file-card');
        var statusBadge = document.getElementById('new-file-status-badge');

        if (editInput) editInput.value = '';
        if (editBtn) {
            editBtn.disabled = true;
            editBtn.title = 'Pilih atau seret berkas baru pengganti terlebih dahulu';
        }
        if (editZone) {
            editZone.classList.remove('hidden');
            editZone.style.display = 'flex';
        }
        if (newCard) {
            newCard.classList.add('hidden');
            newCard.style.display = 'none';
        }
        if (statusBadge) {
            statusBadge.textContent = 'Belum ada berkas';
            statusBadge.className = 'text-[0.62rem] font-mono px-2 py-0.5 rounded border bg-gray-50 text-gray-500 border-gray-200';
        }
    };

    function displayUploadError(msg) {
        if (typeof showToastError === 'function') {
            showToastError(msg);
        }
    }

    window.applyEditFile = function(file) {
        var editBtn     = document.getElementById('btn-submit-edit-doc');
        var editZone    = document.getElementById('edit-drop-zone');
        var newCard     = document.getElementById('edit-new-file-card');
        var statusBadge = document.getElementById('new-file-status-badge');
        var newNameEl   = document.getElementById('edit-new-filename');
        var newSizeEl   = document.getElementById('edit-new-filesize');
        var newExtEl    = document.getElementById('edit-new-file-ext');
        var editInput   = document.getElementById('edit_upload_file');

        if (!file) {
            cancelNewFileSelection();
            return;
        }

        var allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'mp4'];
        var maxSizeBytes = 10 * 1024 * 1024; // 10 MB
        var ext = file.name.split('.').pop().toLowerCase();
        var sizeMb = (file.size / (1024 * 1024)).toFixed(2);

        if (!allowedExts.includes(ext)) {
            if (editInput) editInput.value = '';
            cancelNewFileSelection();
            var errMsg = "Format berkas '" + file.name + "' tidak didukung. Harap unggah berkas PDF, Word (doc/docx), Gambar (jpg/png), atau Video (mp4).";
            displayUploadError(errMsg);
            return;
        }

        if (file.size > maxSizeBytes) {
            if (editInput) editInput.value = '';
            cancelNewFileSelection();
            var errMsg = "Ukuran berkas '" + file.name + "' (" + sizeMb + " MB) melebihi batas maksimal 10 MB.";
            displayUploadError(errMsg);
            return;
        }

        if (editBtn) {
            editBtn.disabled = false;
            editBtn.title = 'Simpan berkas pengganti';
        }

        if (editZone) {
            editZone.classList.add('hidden');
            editZone.style.display = 'none';
        }
        if (newCard) {
            newCard.classList.remove('hidden');
            newCard.style.display = 'flex';
        }

        if (newNameEl) newNameEl.textContent = file.name;
        if (newSizeEl) newSizeEl.textContent = 'Ukuran: ' + sizeMb + ' MB';
        if (newExtEl) {
            var extUpper = ext.toUpperCase();
            newExtEl.textContent = extUpper.length <= 4 ? extUpper : 'FILE';
        }

        if (statusBadge) {
            statusBadge.textContent = 'Berkas Baru Terpilih';
            statusBadge.className = 'text-[0.62rem] font-mono px-2 py-0.5 rounded border bg-amber-50 text-[#B8860B] border-amber-200 font-semibold';
        }
    };

    // Edit Document Modal
    function openEditDocumentModal(id, type, filename, actionUrl) {
        closeUploadDocumentModal();
        closeDeleteModalShow();

        var form = document.getElementById('edit-document-form');
        var hiddenType = document.getElementById('edit_document_type');
        var badge = document.getElementById('edit-doc-type-badge');
        var nameDisplay = document.getElementById('edit-current-filename');

        form.action = actionUrl;
        hiddenType.value = type;
        badge.textContent = type;
        if (nameDisplay) nameDisplay.textContent = filename;

        // Sync ext badge for old file
        var extEl = document.getElementById('edit-file-ext');
        if (extEl) {
            var ext = filename.split('.').pop().toUpperCase();
            extEl.textContent = ext.length <= 4 ? ext : 'FILE';
        }

        cancelNewFileSelection();

        document.getElementById('edit-document-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.add('modal-open-filter');
    }

    function closeEditDocumentModal() {
        // Jangan tutup jika mekanisme pengaman aktif
        var modal = document.getElementById('edit-document-modal');
        if (modal && modal.dataset.locked === '1') return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.remove('modal-open-filter');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !isUploadingActive) {
            // Hanya tutup modal yang tidak sedang locked
            var editDocModal = document.getElementById('edit-document-modal');
            var editActModal = document.getElementById('edit-activity-modal-show');
            if (!editDocModal || editDocModal.dataset.locked !== '1') closeEditDocumentModal();
            if (!editActModal || editActModal.dataset.locked !== '1') closeEditActivityModalShow();
            closeUploadDocumentModal();
            closeDeleteModalShow();
        }
    });

    // ── Multi-Dropzone & Queue Logic ──
    function handleFileInputChange(category, inputEl) {
        if (!inputEl || !inputEl.files || inputEl.files.length === 0) return;
        var filesArr = [];
        for (var i = 0; i < inputEl.files.length; i++) {
            filesArr.push(inputEl.files[i]);
        }
        inputEl.value = '';
        handleFileSelection(category, filesArr);
    }

    function handleFileSelection(category, fileList) {
        if (!fileList || fileList.length === 0) return;
        var filesArr = Array.from(fileList);
        var allowedExts = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'mp4'];
        var maxSizeBytes = 10 * 1024 * 1024; // 10 MB

        for (var i = 0; i < filesArr.length; i++) {
            var file = filesArr[i];
            var ext = file.name.split('.').pop().toLowerCase();
            var sizeMb = (file.size / (1024 * 1024)).toFixed(2);

            // Validasi Format
            if (!allowedExts.includes(ext)) {
                var errMsgFormat = "Format berkas '" + file.name + "' tidak didukung. Harap unggah berkas PDF, Word (doc/docx), Gambar (jpg/png), atau Video (mp4).";
                displayUploadError(errMsgFormat);
                continue;
            }

            // Validasi Ukuran (> 10MB)
            if (file.size > maxSizeBytes) {
                var errMsgSize = "Ukuran berkas '" + file.name + "' (" + sizeMb + " MB) melebihi batas maksimal 10 MB.";
                displayUploadError(errMsgSize);
                continue;
            }

            var fileId = 'file_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
            queuedUploadFiles.push({
                id: fileId,
                file: file,
                type: category,
                status: 'pending',
                progress: 0,
                error: null
            });
        }
        renderUploadQueue();
    }

    function removeQueuedFile(fileId) {
        if (isUploadingActive) return;
        queuedUploadFiles = queuedUploadFiles.filter(function(item) { return item.id !== fileId; });
        renderUploadQueue();
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text).replace(/&/g, "&amp;")
                           .replace(/</g, "&lt;")
                           .replace(/>/g, "&gt;")
                           .replace(/"/g, "&quot;")
                           .replace(/'/g, "&#039;");
    }

    function renderUploadQueue() {
        var queueList  = document.getElementById('queue-file-list');
        var emptyState = document.getElementById('queue-empty-state');
        var totalBadge = document.getElementById('queue-total-badge');

        var suratCount = 0, notulenCount = 0, dokCount = 0;
        queuedUploadFiles.forEach(function(item) {
            if (item.type === 'surat') suratCount++;
            if (item.type === 'notulen') notulenCount++;
            if (item.type === 'dokumentasi') dokCount++;
        });

        updateCountBadge('count-badge-surat', suratCount);
        updateCountBadge('count-badge-notulen', notulenCount);
        updateCountBadge('count-badge-dokumentasi', dokCount);

        if (totalBadge) totalBadge.textContent = queuedUploadFiles.length + ' berkas terpilih';

        var startUploadBtn = document.getElementById('btn-start-upload');
        if (startUploadBtn) {
            const isEmpty = (queuedUploadFiles.length === 0);
            startUploadBtn.disabled = isEmpty;
            startUploadBtn.title = isEmpty
                ? 'Pilih setidaknya 1 berkas dokumen di kolom sebelah kiri terlebih dahulu'
                : 'Unggah seluruh berkas terpilih';
        }

        if (queuedUploadFiles.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (queueList) {
                queueList.classList.add('hidden');
                queueList.innerHTML = '';
            }
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (queueList) {
            queueList.classList.remove('hidden');
            var html = '';
            queuedUploadFiles.forEach(function(item) {
                var ext = item.file.name.split('.').pop().toUpperCase();
                if (ext.length > 4) ext = 'FILE';
                var sizeMb = (item.file.size / (1024 * 1024)).toFixed(2) + ' MB';
                
                var typeBadgeClass = 'bg-[#F5F3F0] text-[#B8860B] border-[#E8E4DF]';
                var typeLabel = 'Surat';
                if (item.type === 'notulen') {
                    typeBadgeClass = 'bg-[#FAFAF8] text-[#1A1A1A] border-[#E8E4DF]';
                    typeLabel = 'Notulen';
                } else if (item.type === 'dokumentasi') {
                    typeBadgeClass = 'bg-[#FAFAF8] text-[#B8860B] border-[#E8E4DF]';
                    typeLabel = 'Dokumentasi';
                }

                html += '<div class="border border-[#E8E4DF] rounded-lg bg-[#FFFFFF] flex items-center justify-between gap-3 hover:border-[#B8860B] transition-all group" style="padding: 0.75rem 0.9rem; margin-bottom: 0.75rem; display: flex; align-items: center;">' +
                            '<div class="flex items-center gap-3 min-w-0 flex-1" style="display: flex; align-items: center;">' +
                                '<div class="rounded-md bg-[#F5F3F0] border border-[#E8E4DF] text-[#B8860B] font-mono text-[0.6rem] font-bold flex items-center justify-center flex-shrink-0 uppercase group-hover:border-[#B8860B] transition-colors" style="width: 36px; height: 36px; flex-shrink: 0;">' +
                                    ext +
                                '</div>' +
                                '<div class="min-w-0 flex-1" style="display: flex; flex-direction: column; gap: 0.2rem;">' +
                                    '<p class="text-xs font-semibold text-[#1A1A1A] truncate leading-tight" style="margin: 0;" title="' + escapeHtml(item.file.name) + '">' + escapeHtml(item.file.name) + '</p>' +
                                    '<div class="flex items-center gap-2" style="display: flex; align-items: center;">' +
                                        '<span class="inline-flex items-center rounded text-[0.58rem] font-mono font-medium uppercase border ' + typeBadgeClass + '" style="padding: 1px 5px; line-height: 1.4; display: inline-block;">' + typeLabel + '</span>' +
                                        '<span class="text-[0.62rem] text-[#6B6B6B] font-mono">' + sizeMb + '</span>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<button type="button" onclick="removeQueuedFile(\'' + item.id + '\')" class="text-[#9A948D] hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors flex-shrink-0" style="width: 28px; height: 28px; border-radius: 6px; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Hapus dari antrean">' +
                                '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 14px; height: 14px; flex-shrink: 0;">' +
                                    '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>' +
                                '</svg>' +
                            '</button>' +
                        '</div>';
            });
            queueList.innerHTML = html;
        }
    }

    function updateCountBadge(elementId, count) {
        var el = document.getElementById(elementId);
        if (!el) return;
        if (count > 0) {
            el.textContent = count + ' file';
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }

    // ── Batch Upload Engine ──
    var currentUploadXhr = null;
    var uploadOneMinuteTimer = null;

    function startBatchUpload() {
        if (queuedUploadFiles.length === 0) {
            alert('Silakan pilih setidaknya 1 berkas dokumen terlebih dahulu.');
            return;
        }

        isUploadingActive = true;
        document.getElementById('upload-phase-select').classList.add('hidden');
        document.getElementById('upload-phase-progress').classList.remove('hidden');
        document.getElementById('btn-close-upload-modal').classList.add('hidden');
        document.getElementById('progress-footer-actions').classList.add('hidden');

        renderProgressList();

        // 1-Minute Timer: jika upload > 1 menit, munculkan tombol Batalkan Unggahan
        if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
        uploadOneMinuteTimer = setTimeout(onUploadOneMinuteTimeout, 60000);

        uploadNextInQueue(0);
    }

    function onUploadOneMinuteTimeout() {
        if (!isUploadingActive) return;

        var alertBox = document.getElementById('upload-status-alert');
        var alertTitle = document.getElementById('upload-status-title');
        var alertSub = document.getElementById('upload-status-sub');
        var footerActions = document.getElementById('progress-footer-actions');
        var cancelBtn = document.getElementById('btn-cancel-upload-process');
        var retryBtn = document.getElementById('btn-retry-upload');
        var backBtn = document.getElementById('btn-back-selection');

        if (alertBox) alertBox.className = 'p-4 rounded-xl border bg-amber-50 border-amber-200 text-amber-900 flex items-center gap-3';
        if (alertTitle) alertTitle.textContent = 'Proses Berjalan Lebih dari 1 Menit';
        if (alertSub) alertSub.textContent = 'Proses mengunggah masih berlangsung. Anda dapat menunggu atau membatalkan proses jika bermasalah.';

        if (cancelBtn) cancelBtn.classList.remove('hidden');
        if (retryBtn) retryBtn.classList.add('hidden');
        if (backBtn) backBtn.classList.add('hidden');
        if (footerActions) footerActions.classList.remove('hidden');
    }

    function cancelUploadProcess() {
        if (currentUploadXhr) {
            try { currentUploadXhr.abort(); } catch (_) {}
        }
        if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
        isUploadingActive = false;
        queuedUploadFiles = [];
        renderUploadQueue();

        document.getElementById('upload-phase-progress').classList.add('hidden');
        document.getElementById('upload-phase-select').classList.remove('hidden');
        document.getElementById('btn-close-upload-modal').classList.remove('hidden');
        document.getElementById('progress-footer-actions').classList.add('hidden');

        if (typeof showToastError === 'function') {
            showToastError('Proses pengunggahan dokumen telah dibatalkan.');
        }
    }

    function renderProgressList() {
        var container = document.getElementById('progress-file-list');
        if (!container) return;

        var html = '';
        queuedUploadFiles.forEach(function(item) {
            var ext = item.file.name.split('.').pop().toUpperCase();
            if (ext.length > 4) ext = 'FILE';
            
            var typeBadgeClass = 'bg-[#F5F3F0] text-[#B8860B] border-[#E8E4DF]';
            var typeLabel = 'Surat';
            if (item.type === 'notulen') {
                typeBadgeClass = 'bg-[#FAFAF8] text-[#1A1A1A] border-[#E8E4DF]';
                typeLabel = 'Notulen';
            } else if (item.type === 'dokumentasi') {
                typeBadgeClass = 'bg-[#FAFAF8] text-[#B8860B] border-[#E8E4DF]';
                typeLabel = 'Dokumentasi';
            }

            html += '<div id="prog-item-' + item.id + '" class="border border-[#E8E4DF] rounded-xl bg-[#FFFFFF] shadow-2xs" style="padding: 0.9rem 1rem; margin-bottom: 0.75rem; display: flex; flex-direction: column; gap: 0.65rem;">' +
                        '<div class="flex items-center justify-between gap-3.5" style="display: flex; align-items: center; justify-content: space-between;">' +
                            '<div class="flex items-center gap-3.5 min-w-0 flex-1" style="display: flex; align-items: center;">' +
                                '<div class="rounded-lg bg-[#FAFAF8] border border-[#E8E4DF] text-[#B8860B] font-mono text-[0.65rem] font-bold flex items-center justify-center flex-shrink-0 uppercase shadow-2xs" style="width: 36px; height: 36px; margin-right: 1rem;">' +
                                    ext +
                                '</div>' +
                                '<div class="min-w-0 flex-1" style="display: flex; flex-direction: column; gap: 0.15rem;">' +
                                    '<p class="text-xs font-semibold text-[#1A1A1A] truncate" style="margin: 0;" title="' + escapeHtml(item.file.name) + '">' + escapeHtml(item.file.name) + '</p>' +
                                    '<span class="inline-flex items-center rounded text-[0.58rem] font-mono font-medium uppercase border ' + typeBadgeClass + '" style="padding: 2px 6px; width: fit-content; line-height: 1; display: inline-block;">' + typeLabel + '</span>' +
                                '</div>' +
                            '</div>' +
                            '<span id="prog-badge-' + item.id + '" class="text-xs font-mono text-[#6B6B6B] font-medium flex-shrink-0">0%</span>' +
                        '</div>' +
                        '<div class="w-full rounded-full bg-[#E8E4DF] overflow-hidden" style="height: 8px;">' +
                            '<div id="prog-bar-' + item.id + '" class="h-full bg-[#B8860B] transition-all duration-150 rounded-full" style="width:0%;"></div>' +
                        '</div>' +
                        '<p id="prog-err-' + item.id + '" class="hidden text-[0.65rem] text-red-600 font-mono"></p>' +
                    '</div>';
        });
        container.innerHTML = html;
    }

    function uploadNextInQueue(index) {
        if (index >= queuedUploadFiles.length) {
            if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
            var hasFailures = queuedUploadFiles.some(function(item) { return item.status === 'failed'; });
            if (!hasFailures) {
                updateOverallProgress(100);
                showUploadSuccessState();
            } else {
                showUploadFailureState();
            }
            return;
        }

        var item = queuedUploadFiles[index];
        item.status = 'uploading';

        var formData = new FormData();
        formData.append('document_type', item.type);
        formData.append('file', item.file);
        formData.append('_token', '{{ csrf_token() }}');

        var xhr = new XMLHttpRequest();
        currentUploadXhr = xhr;
        xhr.open('POST', '{{ route("admin.activities.documents.store", $activity) }}', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percent = Math.round((e.loaded / e.total) * 100);
                item.progress = percent;

                var bar = document.getElementById('prog-bar-' + item.id);
                var badge = document.getElementById('prog-badge-' + item.id);
                if (bar) bar.style.width = percent + '%';
                if (badge) badge.textContent = percent + '%';

                var totalProgressSum = 0;
                queuedUploadFiles.forEach(function(f) { totalProgressSum += (f.progress || 0); });
                var overallPercent = Math.round(totalProgressSum / queuedUploadFiles.length);
                updateOverallProgress(overallPercent);
            }
        };

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                item.status = 'success';
                item.progress = 100;
                var bar = document.getElementById('prog-bar-' + item.id);
                var badge = document.getElementById('prog-badge-' + item.id);
                if (bar) {
                    bar.style.width = '100%';
                    bar.classList.remove('bg-[#B8860B]');
                    bar.classList.add('bg-emerald-600');
                }
                if (badge) {
                    badge.textContent = '100% Selesai';
                    badge.className = 'text-xs font-mono font-semibold text-emerald-600';
                }
                uploadNextInQueue(index + 1);
            } else {
                item.status = 'failed';
                var errMessage = 'Gagal mengunggah berkas.';
                try {
                    var json = JSON.parse(xhr.responseText);
                    if (json.message) errMessage = json.message;
                    if (json.errors && json.errors.file) errMessage = json.errors.file[0];
                } catch (_) {}

                item.error = errMessage;
                var bar = document.getElementById('prog-bar-' + item.id);
                var badge = document.getElementById('prog-badge-' + item.id);
                var errEl = document.getElementById('prog-err-' + item.id);

                if (bar) {
                    bar.classList.remove('bg-[#B8860B]');
                    bar.classList.add('bg-red-500');
                }
                if (badge) {
                    badge.textContent = 'Gagal';
                    badge.className = 'text-xs font-mono font-semibold text-red-600';
                }
                if (errEl) {
                    errEl.textContent = errMessage;
                    errEl.classList.remove('hidden');
                }
                uploadNextInQueue(index + 1);
            }
        };

        xhr.onerror = function() {
            item.status = 'failed';
            item.error = 'Koneksi terputus saat mengunggah.';
            uploadNextInQueue(index + 1);
        };

        xhr.send(formData);
    }

    function updateOverallProgress(percent) {
        var bar = document.getElementById('overall-progress-bar');
        var badge = document.getElementById('overall-progress-percent');
        if (bar) bar.style.width = percent + '%';
        if (badge) badge.textContent = percent + '%';
    }

    function showUploadSuccessState() {
        if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
        var alertBox = document.getElementById('upload-status-alert');
        var alertTitle = document.getElementById('upload-status-title');
        var alertSub = document.getElementById('upload-status-sub');

        if (alertBox) alertBox.className = 'p-4 rounded-xl border bg-emerald-50 border-emerald-200 text-emerald-900 flex items-center gap-3';
        if (alertTitle) alertTitle.textContent = 'Unggah Selesai!';
        if (alertSub) alertSub.textContent = 'Seluruh dokumen berhasil disimpan ke arsip. Menutup otomatis dalam 2 detik...';

        setTimeout(function() {
            closeUploadDocumentModal();
            window.location.reload();
        }, 2000);
    }

    function showUploadFailureState() {
        if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
        isUploadingActive = false;
        var alertBox = document.getElementById('upload-status-alert');
        var alertTitle = document.getElementById('upload-status-title');
        var alertSub = document.getElementById('upload-status-sub');
        var footerActions = document.getElementById('progress-footer-actions');
        var cancelBtn = document.getElementById('btn-cancel-upload-process');
        var retryBtn = document.getElementById('btn-retry-upload');
        var backBtn = document.getElementById('btn-back-selection');
        var closeBtn = document.getElementById('btn-close-upload-modal');

        if (alertBox) alertBox.className = 'p-4 rounded-xl border bg-red-50 border-red-200 text-red-900 flex items-center gap-3';
        if (alertTitle) alertTitle.textContent = 'Beberapa Unggahan Gagal';
        if (alertSub) alertSub.textContent = 'Silakan periksa rincian kesalahan di bawah ini dan coba lagi.';
        
        if (cancelBtn) cancelBtn.classList.add('hidden');
        if (retryBtn) retryBtn.classList.remove('hidden');
        if (backBtn) backBtn.classList.remove('hidden');
        if (footerActions) footerActions.classList.remove('hidden');
        if (closeBtn) closeBtn.classList.remove('hidden');
    }

    function retryUpload() {
        if (uploadOneMinuteTimer) clearTimeout(uploadOneMinuteTimer);
        uploadOneMinuteTimer = setTimeout(onUploadOneMinuteTimeout, 60000);

        document.getElementById('progress-footer-actions').classList.add('hidden');
        var alertBox = document.getElementById('upload-status-alert');
        var alertTitle = document.getElementById('upload-status-title');
        var alertSub = document.getElementById('upload-status-sub');

        if (alertBox) alertBox.className = 'p-4 rounded-xl border bg-amber-50 border-amber-200 text-amber-900 flex items-center gap-3';
        if (alertTitle) alertTitle.textContent = 'Mencoba Mengunggah Ulang...';
        if (alertSub) alertSub.textContent = 'Harap tunggu hingga proses mengunggah selesai...';

        queuedUploadFiles.forEach(function(item) {
            if (item.status === 'failed') {
                item.status = 'pending';
                item.progress = 0;
                item.error = null;
            }
        });

        renderProgressList();
        uploadNextInQueue(0);
    }

    function backToSelectionPhase() {
        isUploadingActive = false;
        queuedUploadFiles = [];
        renderUploadQueue();
        document.getElementById('upload-phase-progress').classList.add('hidden');
        document.getElementById('upload-phase-select').classList.remove('hidden');
        document.getElementById('btn-close-upload-modal').classList.remove('hidden');
        document.getElementById('progress-footer-actions').classList.add('hidden');
    }

    // ── Helper: Lock / Unlock all interactive elements inside a modal ─
    // PENTING: Input/select/textarea TIDAK di-disable — disabled fields tidak terkirim ke server!
    // Pengamanan: overlay visual + disable tombol saja.
    function lockModalForm(modalId) {
        var modal = document.getElementById(modalId);
        if (!modal) return;
        // Hanya disable tombol, bukan input/select/textarea
        modal.querySelectorAll('button').forEach(function (el) {
            el.dataset.wasDisabled = el.disabled ? '1' : '0';
            el.disabled = true;
        });
        var box = modal.querySelector('.modal-box');
        if (box) {
            box.style.position = 'relative';
            var ol = document.getElementById(modalId + '-lock-overlay');
            if (!ol) {
                ol = document.createElement('div');
                ol.id = modalId + '-lock-overlay';
                ol.style.cssText = 'position:absolute;inset:0;z-index:50;cursor:not-allowed;border-radius:inherit;background:rgba(255,255,255,0.38);pointer-events:all;';
                box.appendChild(ol);
            }
        }
        modal.dataset.locked = '1';
    }


    // Hook: Edit Kegiatan form submit (show page)
    (function () {
        var editActForm = document.getElementById('edit-activity-form-show');
        var editActBtn  = document.getElementById('btn-show-edit-submit');
        if (editActForm && editActBtn) {
            editActForm.addEventListener('submit', function () {
                lockModalForm('edit-activity-modal-show');
                var rect = editActBtn.getBoundingClientRect();
                editActBtn.style.width  = rect.width  + 'px';
                editActBtn.style.height = rect.height + 'px';
                editActBtn.innerHTML = '<span style="display:inline-block;width:1em;height:1em;border:2px solid currentColor;border-top-color:transparent;border-radius:50%;animation:btnSpin 0.65s linear infinite;"></span>';
            });
        }
    })();


    // (edit-document-form submit handler sudah didaftarkan di DOMContentLoaded di atas)

    // Attach Drag & Drop for 3 categories

    (function () {
        ['surat', 'notulen', 'dokumentasi'].forEach(function(cat) {
            var zone = document.getElementById('drop-zone-' + cat);
            var input = document.getElementById('input_file_' + cat);

            if (zone && input) {
                input.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        var filesArr = Array.from(this.files);
                        this.value = '';
                        handleFileSelection(cat, filesArr);
                    }
                });

                zone.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    zone.classList.add('border-[#B8860B]', 'bg-[#FAFAF8]');
                    zone.classList.remove('border-[#E8E4DF]');
                });
                zone.addEventListener('dragleave', function(e) {
                    if (!zone.contains(e.relatedTarget)) {
                        zone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                        zone.classList.add('border-[#E8E4DF]');
                    }
                });
                zone.addEventListener('drop', function(e) {
                    e.preventDefault();
                    zone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                    zone.classList.add('border-[#E8E4DF]');
                    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        handleFileSelection(cat, Array.from(e.dataTransfer.files));
                    }
                });
            }
        });

        // Edit Document Form Drag & Drop
        var editZone  = document.getElementById('edit-drop-zone');
        var editInput = document.getElementById('edit_upload_file');
        var editForm  = document.getElementById('edit-document-form');
        var editBtn   = document.getElementById('btn-submit-edit-doc');

        if (editZone && editInput) {
            editInput.addEventListener('change', function () { applyEditFile(this.files[0]); });

            editZone.addEventListener('dragover', function (e) {
                e.preventDefault();
                editZone.classList.add('border-[#B8860B]', 'bg-[#FAFAF8]');
                editZone.classList.remove('border-[#E8E4DF]');
            });
            editZone.addEventListener('dragleave', function (e) {
                if (!editZone.contains(e.relatedTarget)) resetEditZone();
            });
            editZone.addEventListener('drop', function (e) {
                e.preventDefault();
                resetEditZone();
                var file = e.dataTransfer.files[0];
                if (file) {
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    editInput.files = dt.files;
                    applyEditFile(file);
                }
            });

            function resetEditZone() {
                editZone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                editZone.classList.add('border-[#E8E4DF]');
            }
        }
    })();
    </script>


</x-layouts.admin>
