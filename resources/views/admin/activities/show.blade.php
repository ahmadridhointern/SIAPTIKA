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
                    <span class="text-xs text-[#6B6B6B] font-mono">Pembuat: <span class="text-[#1A1A1A] font-semibold">{{ $activity->user->name }}</span></span>
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

        {{-- ══ KANAN (1/3) — Upload + Dokumen ══ --}}
        <div class="space-y-5">

            {{-- ① Unggah Dokumen — kompak --}}
            <div class="card-serif bg-[#FFFFFF]">
                <div class="px-5 py-4 pt-4 pb-4 border-b border-[#F5F3F0]">
                    <span class="small-caps text-[0.65rem]">Unggah Dokumen</span>
                </div>

                @if($errors->any())
                    <div class="mx-5 mt-3 p-3 bg-red-50 border border-red-200 rounded space-y-0.5">
                        @foreach($errors->all() as $error)
                            <p class="text-xs text-red-600 font-mono leading-snug">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="form-upload-doc"
                      method="POST"
                      action="{{ route('admin.activities.documents.store', $activity) }}"
                      enctype="multipart/form-data"
                      class="p-5 space-y-3">
                    @csrf

                    {{-- Jenis Dokumen --}}
                    <select id="document_type" name="document_type"
                            class="input-serif text-sm pr-8 appearance-none cursor-pointer
                                   {{ $errors->has('document_type') ? 'border-red-400' : '' }}"
                            style="height:2.5rem;
                                   background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B6B6B' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                                   background-repeat:no-repeat;
                                   background-position:right 0.625rem center;
                                   background-size:0.875rem;">
                        <option value="" disabled {{ old('document_type') ? '' : 'selected' }}>— Pilih jenis —</option>
                        <option value="surat"       {{ old('document_type') === 'surat'       ? 'selected' : '' }}>Surat / Dokumen</option>
                        <option value="notulen"     {{ old('document_type') === 'notulen'     ? 'selected' : '' }}>Notulen</option>
                        <option value="dokumentasi" {{ old('document_type') === 'dokumentasi' ? 'selected' : '' }}>Dokumentasi</option>
                    </select>

                    {{-- Drop Zone --}}
                    <div id="drop-zone"
                         class="flex items-center gap-3 w-full border border-dashed rounded-md px-4 py-3
                                cursor-pointer transition-all duration-150
                                {{ $errors->has('file') ? 'border-red-300 bg-red-50' : 'border-[#E8E4DF] hover:border-[#B8860B] hover:bg-[#FAFAF8]' }}"
                         onclick="document.getElementById('upload_file').click()">

                        <svg id="drop-icon" class="w-5 h-5 flex-shrink-0 text-[#C9C0B5] transition-colors duration-150" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>

                        <div class="min-w-0 flex-1">
                            <p id="drop-placeholder" class="text-xs font-mono text-[#6B6B6B]">
                                Seret atau klik untuk memilih berkas
                            </p>
                            <p id="drop-file-name" class="hidden text-xs font-semibold text-[#1A1A1A] font-mono truncate"></p>
                            <p class="text-[0.6rem] text-[#B8860B]/70 font-mono mt-0.5">PDF · Word · JPG · PNG · MP4 · Maks. 10 MB</p>
                        </div>
                    </div>

                    <input id="upload_file" name="file" type="file"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                           class="sr-only">

                    <button id="btn-upload-doc" type="submit"
                            class="btn-primary w-full justify-center"
                            style="min-height:2.5rem;">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        Unggah
                    </button>
                </form>
            </div>

            {{-- ② Daftar Dokumen & Lampiran — paginated & organized by type --}}
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
                            <p class="text-[0.6rem] text-[#6B6B6B]/60 font-mono">Unggah dokumen menggunakan form di atas</p>
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
    <div id="edit-document-modal" class="modal-backdrop hidden"
         onclick="if(event.target===event.currentTarget) closeEditDocumentModal()">
        <div class="modal-box modal-box-sm">
            <div class="px-8 pt-7 pb-6">
                <div class="flex items-center justify-between pb-4 border-b border-[#F5F3F0] mb-5">
                    <div>
                        <h2 class="font-serif text-xl text-[#1A1A1A]">Edit Dokumen Arsip</h2>
                        <p class="text-xs text-[#6B6B6B] font-mono mt-0.5">Perbarui jenis atau ganti berkas dokumen</p>
                    </div>
                    <button type="button" onclick="closeEditDocumentModal()" class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="edit-document-form" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Jenis Dokumen --}}
                    <div>
                        <label for="edit_document_type" class="block text-xs font-mono uppercase tracking-wider text-[#6B6B6B] mb-1.5">
                            Jenis Dokumen <span class="text-red-500">*</span>
                        </label>
                        <select id="edit_document_type" name="document_type" required
                                class="input-serif text-sm pr-8 appearance-none cursor-pointer"
                                style="height:2.5rem;
                                       background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B6B6B' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                                       background-repeat:no-repeat;
                                       background-position:right 0.625rem center;
                                       background-size:0.875rem;">
                            <option value="surat">Surat / Dokumen</option>
                            <option value="notulen">Notulen</option>
                            <option value="dokumentasi">Dokumentasi</option>
                        </select>
                    </div>

                    {{-- Ganti Berkas (Opsional) --}}
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#6B6B6B] mb-1.5">
                            Ganti Berkas <span class="text-gray-400 font-normal lowercase">(opsional)</span>
                        </label>

                        <div id="edit-drop-zone"
                             class="flex items-center gap-3 w-full border border-dashed border-[#E8E4DF] hover:border-[#B8860B] hover:bg-[#FAFAF8] rounded-md px-4 py-3 cursor-pointer transition-all duration-150"
                             onclick="document.getElementById('edit_upload_file').click()">
                            <svg id="edit-drop-icon" class="w-5 h-5 flex-shrink-0 text-[#C9C0B5] transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <div class="min-w-0 flex-1">
                                <p id="edit-current-filename" class="text-xs font-mono text-[#1A1A1A] font-semibold truncate"></p>
                                <p id="edit-drop-hint" class="text-[0.65rem] text-[#6B6B6B] font-mono mt-0.5">Klik atau seret berkas baru untuk mengganti</p>
                            </div>
                        </div>

                        <input id="edit_upload_file" name="file" type="file"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                               class="sr-only">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button id="btn-submit-edit-doc" type="submit" class="btn-primary flex-1 justify-center" style="min-height:2.5rem;">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditDocumentModal()" class="btn-secondary flex-1 justify-center" style="min-height:2.5rem;">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endpush

    {{-- ── Scripts ── --}}
    <script>
    // Delete Activity Modal
    function openDeleteModalShow(id, title) {
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
        var form = document.getElementById('edit-document-form');
        var selectType = document.getElementById('edit_document_type');
        var nameDisplay = document.getElementById('edit-current-filename');
        var hintDisplay = document.getElementById('edit-drop-hint');
        var fileInput = document.getElementById('edit_upload_file');
        var icon = document.getElementById('edit-drop-icon');

        form.action = actionUrl;
        selectType.value = type;
        nameDisplay.textContent = 'Berkas saat ini: ' + filename;
        hintDisplay.textContent = 'Klik atau seret berkas baru jika ingin mengganti berkas ini';
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
        if (e.key === 'Escape') {
            closeDeleteModalShow();
            closeEditDocumentModal();
        }
    });

    // Upload Form (Store) & Edit Form Drag & Drop
    (function () {
        // Upload Form (Store)
        var zone  = document.getElementById('drop-zone');
        var input = document.getElementById('upload_file');
        var icon  = document.getElementById('drop-icon');
        var ph    = document.getElementById('drop-placeholder');
        var fn    = document.getElementById('drop-file-name');
        var form  = document.getElementById('form-upload-doc');
        var btn   = document.getElementById('btn-upload-doc');

        if (zone && input) {
            input.addEventListener('change', function () { applyFile(this.files[0]); });

            zone.addEventListener('dragover', function (e) {
                e.preventDefault();
                zone.classList.add('border-[#B8860B]', 'bg-[#FAFAF8]');
                zone.classList.remove('border-[#E8E4DF]');
            });
            zone.addEventListener('dragleave', function (e) {
                if (!zone.contains(e.relatedTarget)) resetZone();
            });
            zone.addEventListener('drop', function (e) {
                e.preventDefault();
                resetZone();
                var file = e.dataTransfer.files[0];
                if (file) {
                    var dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    applyFile(file);
                }
            });

            if (form && btn) {
                form.addEventListener('submit', function () {
                    if (typeof setButtonLoading === 'function') setButtonLoading(btn, true);
                });
            }

            function applyFile(file) {
                if (!file) return;
                fn.textContent = file.name;
                fn.classList.remove('hidden');
                ph.classList.add('hidden');
                icon.classList.remove('text-[#C9C0B5]');
                icon.classList.add('text-[#B8860B]');
            }

            function resetZone() {
                zone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                zone.classList.add('border-[#E8E4DF]');
            }
        }

        // Edit Document Form
        var editZone  = document.getElementById('edit-drop-zone');
        var editInput = document.getElementById('edit_upload_file');
        var editForm  = document.getElementById('edit-document-form');
        var editBtn   = document.getElementById('btn-submit-edit-doc');
        var editHint  = document.getElementById('edit-drop-hint');
        var editIcon  = document.getElementById('edit-drop-icon');

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
                editHint.textContent = 'Berkas pengganti: ' + file.name;
                editIcon.classList.remove('text-[#C9C0B5]');
                editIcon.classList.add('text-[#B8860B]');
            }

            function resetEditZone() {
                editZone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                editZone.classList.add('border-[#E8E4DF]');
            }
        }
    })();
    </script>

</x-layouts.admin>

