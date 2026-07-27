<x-layouts.admin title="Detail Kegiatan">

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
                <a href="{{ route('admin.activities.index') }}" class="back-btn" title="Kembali ke Daftar Kegiatan">
                    <svg class="back-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span class="back-btn-text">Kembali</span>
                </a>
                @if(!$activity->activity_date->lt(today()))
                    <a href="{{ route('admin.activities.index', ['edit' => $activity->id]) }}" class="btn-primary">
                        Ubah Kegiatan
                    </a>
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
                <div class="px-8 py-4 border-b border-[#F5F3F0] flex flex-wrap items-center justify-between gap-x-6 gap-y-1">
                    <span class="text-xs text-[#6B6B6B] font-mono">Pembuat: <span class="text-[#1A1A1A]">{{ $activity->user->name }}</span></span>
                    <span class="text-xs text-[#6B6B6B] font-mono">Dibuat: <span class="text-[#1A1A1A]">{{ $activity->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB</span></span>
                </div>

                <div class="p-8 space-y-6">

                {{-- Meta: Tanggal · Tempat · Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pb-6 border-b border-[#F5F3F0]">


                    <div>
                        <span class="small-caps text-[0.65rem] block mb-1">Waktu Pelaksanaan</span>
                        <div class="text-[#1A1A1A] font-medium text-base">
                            {{ $activity->activity_date->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                        <div class="text-[#6B6B6B] text-xs font-mono mt-0.5">
                            Pukul {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                        </div>
                    </div>

                    <div>
                        <span class="small-caps text-[0.65rem] block mb-1">Tempat / Ruangan</span>
                        <div class="text-[#1A1A1A] font-medium text-base">{{ $activity->location }}</div>
                        <div class="text-[#6B6B6B] text-xs mt-0.5">Bidang APTIKA Diskominfotik Riau</div>
                    </div>

                    <div>
                        <span class="small-caps text-[0.65rem] block mb-1">Status</span>
                        @if($activity->status === 'completed')
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200">Selesai</span>
                        @elseif($activity->activity_date->isToday())
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">Hari Ini</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">Terjadwal</span>
                        @endif
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div>
                    <span class="small-caps text-[0.65rem] block mb-2">Deskripsi & Agenda</span>
                    <div class="text-sm text-[#1A1A1A] leading-relaxed whitespace-pre-line" style="font-family: 'Source Sans 3', system-ui, sans-serif;">
                        {!! $activity->description ? e($activity->description) : '<em class="text-[#6B6B6B]">Tidak ada deskripsi kegiatan.</em>' !!}
                    </div>
                </div>

                </div>{{-- /p-8 --}}

            </div>{{-- /card-serif --}}

            {{-- Danger Zone --}}
            @if(!$activity->activity_date->lt(today()) && $documents->total() === 0)
                <div class="card-serif p-6 bg-[#FFFFFF] border-red-200">
                    <span class="small-caps text-[0.65rem] text-red-600 block mb-2">Zona Bahaya</span>
                    <p class="text-xs text-[#6B6B6B] leading-relaxed mb-4">
                        Penghapusan kegiatan bersifat permanen dan tidak dapat dibatalkan.
                    </p>
                    <button type="button"
                            onclick="openDeleteModalShow({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                            class="w-full text-center text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-red-200 text-red-600 hover:bg-red-50 transition-all duration-200 cursor-pointer"
                            style="background:none;">
                        Hapus Kegiatan
                    </button>
                </div>
            @endif

        </div>

        {{-- ══ KANAN (1/3) — Dokumen & Lampiran ══ --}}
        <div class="space-y-5">

            {{-- Dokumen & Lampiran — paginated & organized by type --}}
            <div class="card-serif bg-[#FFFFFF]">
                <div class="px-5 pt-4 pb-3 border-b border-[#F5F3F0] flex items-center justify-between">
                    <span class="small-caps text-[0.65rem]">Dokumen & Lampiran</span>
                    <span class="text-[0.6rem] font-mono text-[#6B6B6B]">
                        {{ $documents->total() }} berkas
                    </span>
                </div>

                {{-- Filter Jenis Dokumen (Segmented Tabs) --}}
                <div class="px-5 py-2.5 border-b border-[#F5F3F0] bg-[#FAFAF8] flex flex-wrap gap-1.5 text-xs font-mono">
                    <a href="{{ route('admin.activities.show', $activity) }}"
                       class="px-2.5 py-1 rounded transition-colors duration-150 {{ !request('type') ? 'bg-[#1A1A1A] text-[#FFFFFF] font-medium' : 'text-[#6B6B6B] hover:text-[#1A1A1A] hover:bg-[#E8E4DF]/50' }}">
                        Semua ({{ $documentCounts['all'] }})
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'surat']) }}"
                       class="px-2.5 py-1 rounded transition-colors duration-150 {{ request('type') === 'surat' ? 'bg-[#B8860B] text-[#FFFFFF] font-medium' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]/50' }}">
                        Surat ({{ $documentCounts['surat'] }})
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'notulen']) }}"
                       class="px-2.5 py-1 rounded transition-colors duration-150 {{ request('type') === 'notulen' ? 'bg-[#B8860B] text-[#FFFFFF] font-medium' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]/50' }}">
                        Notulen ({{ $documentCounts['notulen'] }})
                    </a>
                    <a href="{{ route('admin.activities.show', [$activity, 'type' => 'dokumentasi']) }}"
                       class="px-2.5 py-1 rounded transition-colors duration-150 {{ request('type') === 'dokumentasi' ? 'bg-[#B8860B] text-[#FFFFFF] font-medium' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]/50' }}">
                        Dokumentasi ({{ $documentCounts['dokumentasi'] }})
                    </a>
                </div>

                <div class="divide-y divide-[#F5F3F0]">
                    @if($documents->total() === 0)
                        <div class="py-12 flex flex-col items-center gap-2 text-center">
                            <svg class="w-8 h-8 text-[#E8E4DF]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-xs font-mono text-[#6B6B6B]">
                                {{ request('type') ? 'Tidak ada dokumen untuk jenis "'.request('type').'"' : 'Belum ada dokumen' }}
                            </p>
                            <p class="text-[0.6rem] text-[#6B6B6B]/60 font-mono">Klik tombol di bawah untuk mengunggah dokumen</p>
                        </div>
                    @else
                        @foreach($documents as $doc)
                            <div class="px-5 py-4 flex items-start justify-between gap-3 hover:bg-[#FAFAF8] transition-colors duration-150">
                                <div class="min-w-0 flex-1">
                                    <div class="text-xs font-semibold text-[#1A1A1A] truncate leading-snug" title="{{ $doc->file_name }}">
                                        {{ $doc->file_name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.55rem] font-mono font-medium uppercase tracking-wider text-[#B8860B] border border-amber-200" style="background-color:#FAFAF8;">
                                            {{ $doc->document_type }}
                                        </span>
                                        <span class="text-[0.6rem] text-[#6B6B6B] font-mono">
                                            {{ $doc->created_at->isoFormat('D MMM YYYY') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Tombol Aksi: Ubah + Buka + Unduh --}}
                                <div class="flex items-center gap-2.5 flex-shrink-0">
                                    <button type="button"
                                            onclick="openEditDocumentModal({{ $doc->id }}, '{{ $doc->document_type }}', '{{ addslashes($doc->file_name) }}', '{{ route('admin.documents.update', $doc) }}')"
                                            class="text-[0.65rem] font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150"
                                            title="Edit Dokumen">
                                        Ubah ✎
                                    </button>
                                    <a href="{{ route('admin.documents.show', $doc) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="text-[0.65rem] font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors duration-150"
                                       title="Buka Dokumen">
                                        Buka ↗
                                    </a>
                                    <a href="{{ route('admin.documents.download', $doc) }}"
                                       class="text-[0.65rem] font-mono font-semibold text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors duration-150"
                                       title="Unduh Dokumen">
                                        Unduh ↓
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Pagination links --}}
                @if($documents->hasPages())
                    <div class="px-5 py-3 border-t border-[#F5F3F0]">
                        {{ $documents->links() }}
                    </div>
                @endif

                {{-- Trigger Modal Unggah Dokumen di bagian paling bawah section --}}
                <div class="p-4 border-t border-[#F5F3F0] bg-[#FFFFFF]">
                    <button type="button"
                            onclick="openUploadDocumentModal()"
                            class="btn-primary w-full justify-center gap-2"
                            style="min-height:2.5rem;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        Unggah Dokumen
                    </button>
                </div>
            </div>


        </div>

    </div>

    {{-- ── Modals ── --}}
    @push('modals')
    {{-- Delete Activity Modal --}}
    <div id="show-delete-modal" class="modal-backdrop hidden"
         onclick="if(event.target===event.currentTarget) closeDeleteModalShow()">
        <div class="modal-box modal-box-sm">
            <div class="px-8 pt-7 pb-6">
                <div class="flex items-start gap-4 mb-5">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-serif text-xl text-[#1A1A1A] mb-1">Hapus Kegiatan?</h2>
                        <p class="text-sm text-[#6B6B6B]">
                            Anda akan menghapus <strong id="show-delete-name" class="text-[#1A1A1A]"></strong>. Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>
                <form id="show-delete-form" method="POST" action="{{ route('admin.activities.destroy', $activity->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="flex-1 text-center text-xs font-mono font-semibold py-2.5 px-4 rounded border border-red-300 text-red-600 bg-red-50 hover:bg-red-100 transition-all duration-150 cursor-pointer">
                            Ya, Hapus
                        </button>
                        <button type="button" onclick="closeDeleteModalShow()" class="btn-secondary flex-1 justify-center">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Document Modal --}}
    <div id="edit-document-modal" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget) closeEditDocumentModal()">
        <div class="modal-box">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Edit Dokumen Arsip</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Perbarui jenis atau ganti berkas dokumen. Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
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

                    <div class="space-y-5">
                        {{-- Jenis Dokumen --}}
                        <div>
                            <label for="edit_document_type" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                                Jenis Dokumen <span class="text-red-500">*</span>
                            </label>
                            <select id="edit_document_type" name="document_type" required
                                    class="input-serif pr-8 appearance-none cursor-pointer"
                                    style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B6B6B' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                                           background-repeat:no-repeat;
                                           background-position:right 0.75rem center;
                                           background-size:0.875rem;">
                                <option value="surat">Surat / Dokumen</option>
                                <option value="notulen">Notulen</option>
                                <option value="dokumentasi">Dokumentasi</option>
                            </select>
                        </div>

                        {{-- Ganti Berkas (Opsional) --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                                Ganti Berkas <span class="text-xs font-normal text-[#6B6B6B]">(opsional)</span>
                            </label>

                            {{-- Large Centered Drop Zone --}}
                            <div id="edit-drop-zone"
                                 class="flex flex-col items-center justify-center p-7 border-2 border-dashed rounded-xl cursor-pointer transition-all duration-200 text-center border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF]"
                                 onclick="document.getElementById('edit_upload_file').click()">

                                <div class="w-12 h-12 rounded-xl bg-[#F5F3F0] flex items-center justify-center mb-3 text-[#B8860B]">
                                    <svg id="edit-drop-icon" class="w-6 h-6 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/>
                                    </svg>
                                </div>

                                <p class="text-xs text-[#1A1A1A] font-medium">
                                    Seret & lepas berkas baru di sini, atau <span class="text-[#B8860B] font-semibold underline underline-offset-2 hover:text-[#9A7009]">Pilih Berkas Baru</span>
                                </p>
                                <p class="text-[0.7rem] text-[#6B6B6B] mt-1.5 font-mono">
                                    Kosongkan jika tidak ingin mengganti berkas saat ini
                                </p>
                            </div>

                            <input id="edit_upload_file" name="file" type="file"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                                   class="sr-only">

                            {{-- Current & New File Item Card --}}
                            <div class="mt-3 p-3.5 border border-[#E8E4DF] rounded-lg bg-[#FFFFFF] flex items-center justify-between gap-3 shadow-xs">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div id="edit-file-ext" class="w-9 h-9 rounded bg-[#FAFAF8] border border-[#E8E4DF] text-[#B8860B] font-mono text-[0.65rem] font-bold flex items-center justify-center flex-shrink-0 uppercase">
                                        FILE
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p id="edit-current-filename" class="text-xs font-semibold text-[#1A1A1A] font-mono truncate"></p>
                                        <p id="edit-drop-hint" class="text-[0.65rem] text-[#6B6B6B] font-mono mt-0.5">Berkas saat ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-submit-edit-doc" type="submit" class="btn-primary flex-1 justify-center">
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
        <div class="modal-box" style="max-width:44rem;">
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
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-6 modal-form-body">

                    {{-- 3 Category Dropzones Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        {{-- 1. Surat --}}
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide">Surat / Dokumen</span>
                                <span id="count-badge-surat" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-surat" class="relative flex-1 flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-xl transition-all duration-200 text-center border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] hover:shadow-xs group overflow-hidden">
                                <div class="w-11 h-11 rounded-xl bg-[#F5F3F0] flex items-center justify-center mb-3 text-[#B8860B] group-hover:scale-105 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-[#1A1A1A] font-medium leading-snug">
                                    <span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Berkas</span>
                                </p>
                                <p class="text-[0.68rem] text-[#6B6B6B] mt-1">atau seret ke sini</p>
                                <p class="text-[0.6rem] text-[#9A948D] mt-2 font-mono">PDF, Word, JPG (Maks. 10MB)</p>
                                <input id="input_file_surat" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('surat', this)">
                            </div>
                        </div>

                        {{-- 2. Notulen --}}
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide">Notulen</span>
                                <span id="count-badge-notulen" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-notulen" class="relative flex-1 flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-xl transition-all duration-200 text-center border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] hover:shadow-xs group overflow-hidden">
                                <div class="w-11 h-11 rounded-xl bg-[#F5F3F0] flex items-center justify-center mb-3 text-[#B8860B] group-hover:scale-105 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-[#1A1A1A] font-medium leading-snug">
                                    <span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Berkas</span>
                                </p>
                                <p class="text-[0.68rem] text-[#6B6B6B] mt-1">atau seret ke sini</p>
                                <p class="text-[0.6rem] text-[#9A948D] mt-2 font-mono">PDF, Word, JPG (Maks. 10MB)</p>
                                <input id="input_file_notulen" type="file" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('notulen', this)">
                            </div>
                        </div>

                        {{-- 3. Dokumentasi --}}
                        <div class="flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold text-[#1A1A1A] font-serif tracking-wide">Dokumentasi</span>
                                <span id="count-badge-dokumentasi" class="text-[0.6rem] font-mono px-1.5 py-0.5 rounded bg-amber-50 text-[#B8860B] border border-amber-200 hidden">0 file</span>
                            </div>
                            <div id="drop-zone-dokumentasi" class="relative flex-1 flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-xl transition-all duration-200 text-center border-[#E8E4DF] bg-[#FAFAF8] hover:border-[#B8860B] hover:bg-[#FFFFFF] hover:shadow-xs group overflow-hidden">
                                <div class="w-11 h-11 rounded-xl bg-[#F5F3F0] flex items-center justify-center mb-3 text-[#B8860B] group-hover:scale-105 transition-transform duration-200">
                                    <svg class="w-5 h-5 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-[#1A1A1A] font-medium leading-snug">
                                    <span class="text-[#B8860B] font-semibold underline underline-offset-2 group-hover:text-[#9A7009]">Pilih Foto/Video</span>
                                </p>
                                <p class="text-[0.68rem] text-[#6B6B6B] mt-1">atau seret ke sini</p>
                                <p class="text-[0.6rem] text-[#9A948D] mt-2 font-mono">JPG, PNG, MP4 (Maks. 10MB)</p>
                                <input id="input_file_dokumentasi" type="file" multiple accept=".jpg,.jpeg,.png,.mp4"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;"
                                       onchange="handleFileInputChange('dokumentasi', this)">
                            </div>
                        </div>

                    </div>

                    {{-- Daftar Berkas Terpilih (Antrean Upload) --}}
                    <div class="border-t border-[#E8E4DF]" style="margin-top: 2rem; padding-top: 1.5rem;">
                        <div class="flex items-center justify-between" style="margin-bottom: 1rem;">
                            <span class="text-xs font-semibold text-[#1A1A1A] font-serif uppercase tracking-wider">Daftar Berkas Siap Diunggah</span>
                            <span id="queue-total-badge" class="px-2.5 py-0.5 rounded-full bg-[#F5F3F0] text-[#B8860B] font-mono text-[0.68rem] font-medium border border-[#E8E4DF]">0 berkas terpilih</span>
                        </div>

                        <div id="queue-empty-state" class="border border-dashed border-[#E8E4DF] rounded-xl text-center bg-[#FAFAF8] flex flex-col items-center justify-center" style="padding: 2rem 1.5rem; gap: 0.5rem;">
                            <svg class="text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="width: 32px; height: 32px; flex-shrink: 0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-xs font-medium text-[#6B6B6B]" style="margin: 0;">Belum ada berkas yang dipilih</p>
                            <p class="text-[0.68rem] text-[#9A948D]" style="margin: 0;">Silakan klik atau seret berkas ke salah satu kotak di atas.</p>
                        </div>

                        <div id="queue-file-list" class="hidden pr-1" style="max-height: 240px; overflow-y: auto;">
                            {{-- Items dynamically rendered via JS --}}
                        </div>
                    </div>

                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-start-upload" type="button" onclick="startBatchUpload()" class="btn-primary flex-1 justify-center" style="min-height:2.75rem;">
                        Unggah Dokumen
                    </button>
                    <button type="button" onclick="closeUploadDocumentModal()" class="btn-secondary flex-1 justify-center" style="min-height:2.75rem;">
                        Batal
                    </button>
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
                    <button type="button" onclick="retryUpload()" class="btn-primary flex-1 justify-center" style="min-height:2.75rem;">
                        Coba Lagi
                    </button>
                    <button type="button" onclick="backToSelectionPhase()" class="btn-secondary flex-1 justify-center" style="min-height:2.75rem;">
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

    // Upload Document Modal
    function openUploadDocumentModal() {
        closeEditDocumentModal();
        closeDeleteModalShow();
        backToSelectionPhase();
        document.getElementById('upload-document-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.add('modal-open-filter');
    }
    function closeUploadDocumentModal() {
        if (isUploadingActive) return;
        document.getElementById('upload-document-modal').classList.add('hidden');
        document.body.style.overflow = '';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.remove('modal-open-filter');
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

    // Edit Document Modal
    function openEditDocumentModal(id, type, filename, actionUrl) {
        closeUploadDocumentModal();
        closeDeleteModalShow();

        var form = document.getElementById('edit-document-form');
        var selectType = document.getElementById('edit_document_type');
        var nameDisplay = document.getElementById('edit-current-filename');
        var hintDisplay = document.getElementById('edit-drop-hint');
        var fileInput = document.getElementById('edit_upload_file');
        var icon = document.getElementById('edit-drop-icon');

        form.action = actionUrl;
        selectType.value = type;
        nameDisplay.textContent = filename;
        hintDisplay.textContent = 'Berkas saat ini';
        fileInput.value = '';
        icon.classList.remove('text-[#B8860B]');
        icon.classList.add('text-[#C9C0B5]');

        document.getElementById('edit-document-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.add('modal-open-filter');
    }

    function closeEditDocumentModal() {
        document.getElementById('edit-document-modal').classList.add('hidden');
        document.body.style.overflow = '';
        var ac = document.getElementById('app-content');
        if (ac) ac.classList.remove('modal-open-filter');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !isUploadingActive) {
            closeUploadDocumentModal();
            closeDeleteModalShow();
            closeEditDocumentModal();
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
        for (var i = 0; i < filesArr.length; i++) {
            var file = filesArr[i];
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

                html += '<div class="border border-[#E8E4DF] rounded-xl bg-[#FFFFFF] flex items-center justify-between gap-4 shadow-2xs hover:border-[#B8860B] transition-all group" style="padding: 0.85rem 1rem; margin-bottom: 0.75rem; display: flex; align-items: center;">' +
                            '<div class="flex items-center gap-3.5 min-w-0 flex-1" style="display: flex; align-items: center;">' +
                                '<div class="rounded-lg bg-[#FAFAF8] border border-[#E8E4DF] text-[#B8860B] font-mono text-xs font-bold flex items-center justify-center flex-shrink-0 uppercase shadow-2xs group-hover:border-[#B8860B] transition-colors" style="width: 40px; height: 40px; flex-shrink: 0; margin-right: 1rem;">' +
                                    ext +
                                '</div>' +
                                '<div class="min-w-0 flex-1" style="display: flex; flex-direction: column; gap: 0.25rem;">' +
                                    '<p class="text-xs font-semibold text-[#1A1A1A] truncate leading-tight" style="margin: 0;" title="' + escapeHtml(item.file.name) + '">' + escapeHtml(item.file.name) + '</p>' +
                                    '<div class="flex items-center gap-2.5" style="display: flex; align-items: center; gap: 0.65rem;">' +
                                        '<span class="inline-flex items-center rounded text-[0.6rem] font-mono font-medium uppercase border ' + typeBadgeClass + '" style="padding: 2px 6px; line-height: 1; display: inline-block;">' + typeLabel + '</span>' +
                                        '<span class="text-[0.65rem] text-[#6B6B6B] font-mono">' + sizeMb + '</span>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<button type="button" onclick="removeQueuedFile(\'' + item.id + '\')" class="text-[#9A948D] hover:text-red-600 hover:bg-red-50 flex items-center justify-center transition-colors flex-shrink-0" style="width: 32px; height: 32px; border-radius: 8px; background: none; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Hapus dari antrean">' +
                                '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px; flex-shrink: 0;">' +
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
    function startBatchUpload() {
        if (queuedUploadFiles.length === 0) {
            alert('Silakan pilih setidaknya 1 berkas dokumen terlebih dahulu.');
            return;
        }

        isUploadingActive = true;
        document.getElementById('upload-phase-select').classList.add('hidden');
        document.getElementById('upload-phase-progress').classList.remove('hidden');
        document.getElementById('btn-close-upload-modal').classList.add('hidden');

        renderProgressList();
        uploadNextInQueue(0);
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
        isUploadingActive = false;
        var alertBox = document.getElementById('upload-status-alert');
        var alertTitle = document.getElementById('upload-status-title');
        var alertSub = document.getElementById('upload-status-sub');
        var footerActions = document.getElementById('progress-footer-actions');
        var closeBtn = document.getElementById('btn-close-upload-modal');

        if (alertBox) alertBox.className = 'p-4 rounded-xl border bg-red-50 border-red-200 text-red-900 flex items-center gap-3';
        if (alertTitle) alertTitle.textContent = 'Beberapa Unggahan Gagal';
        if (alertSub) alertSub.textContent = 'Silakan periksa rincian kesalahan di bawah ini dan coba lagi.';
        if (footerActions) footerActions.classList.remove('hidden');
        if (closeBtn) closeBtn.classList.remove('hidden');
    }

    function retryUpload() {
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

            if (editForm && editBtn) {
                editForm.addEventListener('submit', function () {
                    if (typeof setButtonLoading === 'function') setButtonLoading(editBtn, true);
                });
            }

            function applyEditFile(file) {
                if (!file) return;
                var nameEl = document.getElementById('edit-current-filename');
                var hintEl = document.getElementById('edit-drop-hint');
                var extEl  = document.getElementById('edit-file-ext');

                if (nameEl) nameEl.textContent = file.name;
                if (hintEl) hintEl.textContent = 'Berkas pengganti baru (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                if (extEl) {
                    var ext = file.name.split('.').pop().toUpperCase();
                    extEl.textContent = ext.length <= 4 ? ext : 'FILE';
                }
            }

            function resetEditZone() {
                editZone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                editZone.classList.add('border-[#E8E4DF]');
            }
        }
    })();
    </script>


</x-layouts.admin>
