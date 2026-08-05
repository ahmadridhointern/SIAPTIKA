<x-layouts.admin title="Arsip Dokumen">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Dokumen Kegiatan</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div>
            <h1 class="font-serif text-4xl md:text-5xl text-[#1A1A1A] tracking-tight">
                Arsip Dokumen
            </h1>
            <p class="mt-2 text-sm text-[#6B6B6B]">
                Kelola seluruh berkas dokumen arsip kegiatan Bidang APTIKA — Diskominfotik Riau.
            </p>
        </div>
    </div>

    @php
        $hasAdvFilter = request()->filled('date_from') || request()->filled('date_to') || request()->filled('activity_date_from') || request()->filled('activity_date_to') || (request()->filled('sort') && request('sort') !== 'newest');
    @endphp

    {{-- Search & Filter Bar --}}
    <div class="card-serif mb-6 bg-[#FFFFFF]">
        <form id="filter-form" method="GET" action="{{ route('admin.archive.index') }}" onsubmit="event.preventDefault();"
              class="p-5">

            {{-- Baris 1: Search + Jenis + Tombol Filter Lanjutan --}}
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-end">

                {{-- Search --}}
                <div class="flex-1">
                    <label for="search" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                        Cari Dokumen
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-[#6B6B6B]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z"/>
                            </svg>
                        </span>
                        <input id="search" type="text" name="search" value="{{ request('search') }}"
                               placeholder="Nama file atau judul kegiatan..."
                               class="input-serif" style="padding-left: 2.5rem; padding-right: 2.5rem;" autocomplete="off">

                        {{-- Clear Search Button --}}
                        <button type="button" id="clear-search" onclick="clearSearchInput()"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-[#6B6B6B] hover:text-[#B8860B] transition-colors {{ request('search') ? '' : 'hidden' }}"
                                style="background:none; border:none; cursor:pointer;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Filter Jenis Dokumen --}}
                <div class="w-full md:w-auto flex-shrink-0">
                    <span class="block mb-2 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Jenis</span>
                    <div class="flex items-center gap-2" style="min-height: 3rem;">
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="type" value="" {{ !$hasAdvFilter && (request('type') === null || request('type') === '') ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Semua</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="type" value="surat" {{ !$hasAdvFilter && request('type') === 'surat' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Surat</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="type" value="notulen" {{ !$hasAdvFilter && request('type') === 'notulen' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Notulen</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="type" value="dokumentasi" {{ !$hasAdvFilter && request('type') === 'dokumentasi' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Dokumentasi</span>
                        </label>
                    </div>
                </div>

                {{-- Tombol Filter Lanjutan --}}
                <div class="flex-shrink-0 flex items-end">
                    <button type="button" id="btn-advanced" onclick="openModal('advanced-filter-modal')" class="btn-advanced-filter {{ $hasAdvFilter ? 'active' : '' }}">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/>
                        </svg>
                        Filter Lanjutan
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- Archive List Container --}}
    <div id="archive-results">
        @include('admin.archive.partials.list')
    </div>

    @push('modals')
    {{-- ================================================================
         ADVANCED FILTER MODAL
         ================================================================ --}}
    <div id="advanced-filter-modal" class="modal-backdrop hidden" onclick="handleBackdropClick(event, 'advanced-filter-modal')">
        <div class="modal-box" style="max-width: 36rem;">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Filter Lanjutan Arsip</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Filter spesifik berkas dokumen arsip.</p>
                </div>
                <button type="button" onclick="closeModal('advanced-filter-modal')"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-8 py-6 space-y-5 modal-form-body overflow-y-auto" style="max-height: 60vh;">
                {{-- Format Berkas --}}
                <div>
                    <label for="file_format" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                        Format Berkas
                    </label>
                    <select id="file_format" name="file_format" class="input-serif adv-filter-field">
                        <option value="" {{ request('file_format') === '' || !request('file_format') ? 'selected' : '' }}>Semua Format</option>
                        <option value="pdf" {{ request('file_format') === 'pdf' ? 'selected' : '' }}>PDF (.pdf)</option>
                        <option value="jpg" {{ request('file_format') === 'jpg' ? 'selected' : '' }}>JPG / JPEG (.jpg, .jpeg)</option>
                        <option value="png" {{ request('file_format') === 'png' ? 'selected' : '' }}>PNG (.png)</option>
                        <option value="word" {{ request('file_format') === 'word' ? 'selected' : '' }}>Word (.doc, .docx)</option>
                        <option value="excel" {{ request('file_format') === 'excel' ? 'selected' : '' }}>Excel (.xls, .xlsx)</option>
                        <option value="ppt" {{ request('file_format') === 'ppt' ? 'selected' : '' }}>PowerPoint (.ppt, .pptx)</option>
                        <option value="zip" {{ request('file_format') === 'zip' ? 'selected' : '' }}>ZIP / RAR (.zip, .rar)</option>
                    </select>
                </div>

                {{-- Tanggal Unggah Dokumen (Compact Mode Switcher) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Tanggal Unggah Dokumen</label>
                        <div class="inline-flex p-0.5 bg-[#F5F3F0] rounded-lg border border-[#E8E4DF] text-[10px] font-mono gap-0.5" role="group">
                            <button type="button" id="btn-upload-mode-single" onclick="setUploadDateMode('single')"
                                    class="px-2 py-0.5 rounded-md font-semibold transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                1 Hari
                            </button>
                            <button type="button" id="btn-upload-mode-range" onclick="setUploadDateMode('range')"
                                    class="px-2 py-0.5 rounded-md font-medium text-[#6B6B6B] hover:text-[#1A1A1A] transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                Rentang
                            </button>
                        </div>
                    </div>

                    {{-- Mode 1: Satu Hari --}}
                    <div id="container-upload-single">
                        <input type="date" id="upload_date_single" oninput="syncUploadSingleDate(this.value)" onchange="syncUploadSingleDate(this.value)" class="date-input adv-filter-field">
                    </div>

                    {{-- Mode 2: Rentang Tanggal --}}
                    <div id="container-upload-range" class="hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="date_from" class="block mb-1 text-[11px] font-mono text-[#6B6B6B]">Dari</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="date-input adv-filter-field">
                            </div>
                            <div>
                                <label for="date_to" class="block mb-1 text-[11px] font-mono text-[#6B6B6B]">Sampai</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="date-input adv-filter-field">
                            </div>
                        </div>
                    </div>
                    <p id="date-validation-msg" class="text-xs text-red-500 mt-1.5 hidden">* Wajib memasukkan Tanggal Unggah.</p>
                </div>

                {{-- Tanggal Kegiatan Asal (Compact Mode Switcher) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Tanggal Kegiatan Asal</label>
                        <div class="inline-flex p-0.5 bg-[#F5F3F0] rounded-lg border border-[#E8E4DF] text-[10px] font-mono gap-0.5" role="group">
                            <button type="button" id="btn-act-mode-single" onclick="setActDateMode('single')"
                                    class="px-2 py-0.5 rounded-md font-semibold transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                1 Hari
                            </button>
                            <button type="button" id="btn-act-mode-range" onclick="setActDateMode('range')"
                                    class="px-2 py-0.5 rounded-md font-medium text-[#6B6B6B] hover:text-[#1A1A1A] transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                Rentang
                            </button>
                        </div>
                    </div>

                    {{-- Mode 1: Satu Hari --}}
                    <div id="container-act-single">
                        <input type="date" id="act_date_single" oninput="syncActSingleDate(this.value)" onchange="syncActSingleDate(this.value)" class="date-input adv-filter-field">
                    </div>

                    {{-- Mode 2: Rentang Tanggal --}}
                    <div id="container-act-range" class="hidden">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="activity_date_from" class="block mb-1 text-[11px] font-mono text-[#6B6B6B]">Dari</label>
                                <input type="date" id="activity_date_from" name="activity_date_from" value="{{ request('activity_date_from') }}" class="date-input adv-filter-field">
                            </div>
                            <div>
                                <label for="activity_date_to" class="block mb-1 text-[11px] font-mono text-[#6B6B6B]">Sampai</label>
                                <input type="date" id="activity_date_to" name="activity_date_to" value="{{ request('activity_date_to') }}" class="date-input adv-filter-field">
                            </div>
                        </div>
                    </div>
                    <p id="act-date-validation-msg" class="text-xs text-red-500 mt-1.5 hidden">* Wajib memasukkan Tanggal Kegiatan.</p>
                </div>

                {{-- Urutkan Berdasarkan --}}
                <div>
                    <label for="sort" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                        Urutkan Berdasarkan
                    </label>
                    <select id="sort" name="sort" class="input-serif adv-filter-field">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru Diunggah</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama Diunggah</option>
                        <option value="activity_date" {{ request('sort') === 'activity_date' ? 'selected' : '' }}>Tanggal Kegiatan Terbaru</option>
                        <option value="az" {{ request('sort') === 'az' ? 'selected' : '' }}>Nama File (A → Z)</option>
                    </select>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                <button type="button" id="btn-apply-modal" onclick="applyModalFilter()" class="btn-primary flex-1 justify-center" style="min-height: 2.75rem;">
                    Terapkan Filter
                </button>
                <button type="button" onclick="closeModal('advanced-filter-modal')" class="btn-secondary flex-1 justify-center" style="min-height: 2.75rem;">
                    Batal
                </button>
            </div>
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
                        {{-- Berkas Saat Ini --}}
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

                        {{-- Divider --}}
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

                        {{-- Berkas Baru --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[0.68rem] font-semibold uppercase tracking-wider text-[#B8860B] font-mono">
                                    Berkas Baru (Pengganti)
                                </span>
                                <span id="new-file-status-badge" class="text-[0.62rem] font-mono px-2 py-0.5 rounded border bg-gray-50 text-gray-500 border-gray-200">
                                    Belum ada berkas
                                </span>
                            </div>

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
    @endpush

    {{-- ── Scripts ── --}}
    <script>
    (function () {
        const form        = document.getElementById('filter-form');
        const searchInput = document.getElementById('search');
        const clearBtn    = document.getElementById('clear-search');
        const resultsBox  = document.getElementById('archive-results');
        const baseUrl     = '{{ route("admin.archive.index") }}';

        let debounceTimer;
        let currentFetch = null;

        function getParams() {
            const params = new URLSearchParams();
            if (searchInput && searchInput.value.trim()) params.append('search', searchInput.value.trim());

            const checkedRadio = form.querySelector('.filter-radio:checked');
            if (checkedRadio && checkedRadio.value) {
                params.append('type', checkedRadio.value);
            }

            const format          = document.getElementById('file_format')?.value;
            const dateFrom        = (document.getElementById('date_from')?.value || '').trim();
            const dateTo          = (document.getElementById('date_to')?.value || '').trim();
            const actDateFrom     = (document.getElementById('activity_date_from')?.value || '').trim();
            const actDateTo       = (document.getElementById('activity_date_to')?.value || '').trim();
            const sort            = document.getElementById('sort')?.value;

            if (format) {
                params.append('file_format', format);
            }

            if (dateFrom && dateTo) {
                params.append('date_from', dateFrom);
                params.append('date_to', dateTo);
            }

            if (actDateFrom && actDateTo) {
                params.append('activity_date_from', actDateFrom);
                params.append('activity_date_to', actDateTo);
            }

            if (sort && sort !== 'newest') {
                params.append('sort', sort);
            }

            return params;
        }

        function hasAnyAdvancedFilterValue() {
            const format      = document.getElementById('file_format')?.value;
            const dateFrom    = (document.getElementById('date_from')?.value || '').trim();
            const dateTo      = (document.getElementById('date_to')?.value || '').trim();
            const actDateFrom = (document.getElementById('activity_date_from')?.value || '').trim();
            const actDateTo   = (document.getElementById('activity_date_to')?.value || '').trim();
            const sort        = document.getElementById('sort')?.value;

            if (format) return true;
            if (dateFrom && dateTo) return true;
            if (actDateFrom && actDateTo) return true;
            if (sort && sort !== 'newest') return true;

            return false;
        }

        let currentUploadDateMode = 'single';
        let currentActDateMode    = 'single';

        // ── Date Mode Switchers ──
        window.setUploadDateMode = function(mode) {
            currentUploadDateMode = mode;
            const btnSingle  = document.getElementById('btn-upload-mode-single');
            const btnRange   = document.getElementById('btn-upload-mode-range');
            const boxSingle  = document.getElementById('container-upload-single');
            const boxRange   = document.getElementById('container-upload-range');
            const dateFrom   = document.getElementById('date_from');
            const dateTo     = document.getElementById('date_to');
            const dateSingle = document.getElementById('upload_date_single');

            const activeClasses   = ['bg-[#FFFFFF]', 'text-[#B8860B]', 'font-bold', 'shadow-2xs', 'border', 'border-[#E8E4DF]'];
            const inactiveClasses = ['bg-transparent', 'text-[#6B6B6B]', 'hover:text-[#1A1A1A]', 'font-medium'];

            if (mode === 'single') {
                if (btnSingle) { btnSingle.classList.add(...activeClasses); btnSingle.classList.remove(...inactiveClasses); }
                if (btnRange)  { btnRange.classList.remove(...activeClasses); btnRange.classList.add(...inactiveClasses); }

                if (boxSingle) boxSingle.classList.remove('hidden');
                if (boxRange)  boxRange.classList.add('hidden');

                if (dateFrom && dateFrom.value) {
                    if (dateSingle) dateSingle.value = dateFrom.value;
                    if (dateTo) dateTo.value = dateFrom.value;
                }
            } else {
                if (btnRange)  { btnRange.classList.add(...activeClasses); btnRange.classList.remove(...inactiveClasses); }
                if (btnSingle) { btnSingle.classList.remove(...activeClasses); btnSingle.classList.add(...inactiveClasses); }

                if (boxRange)  boxRange.classList.remove('hidden');
                if (boxSingle) boxSingle.classList.add('hidden');
            }
            checkModalFilterValidation();
        };

        window.syncUploadSingleDate = function(val) {
            const dateFrom = document.getElementById('date_from');
            const dateTo   = document.getElementById('date_to');
            if (dateFrom) dateFrom.value = val;
            if (dateTo)   dateTo.value   = val;
            checkModalFilterValidation();
        };

        window.setActDateMode = function(mode) {
            currentActDateMode = mode;
            const btnSingle  = document.getElementById('btn-act-mode-single');
            const btnRange   = document.getElementById('btn-act-mode-range');
            const boxSingle  = document.getElementById('container-act-single');
            const boxRange   = document.getElementById('container-act-range');
            const dateFrom   = document.getElementById('activity_date_from');
            const dateTo     = document.getElementById('activity_date_to');
            const dateSingle = document.getElementById('act_date_single');

            const activeClasses   = ['bg-[#FFFFFF]', 'text-[#B8860B]', 'font-bold', 'shadow-2xs', 'border', 'border-[#E8E4DF]'];
            const inactiveClasses = ['bg-transparent', 'text-[#6B6B6B]', 'hover:text-[#1A1A1A]', 'font-medium'];

            if (mode === 'single') {
                if (btnSingle) { btnSingle.classList.add(...activeClasses); btnSingle.classList.remove(...inactiveClasses); }
                if (btnRange)  { btnRange.classList.remove(...activeClasses); btnRange.classList.add(...inactiveClasses); }

                if (boxSingle) boxSingle.classList.remove('hidden');
                if (boxRange)  boxRange.classList.add('hidden');

                if (dateFrom && dateFrom.value) {
                    if (dateSingle) dateSingle.value = dateFrom.value;
                    if (dateTo) dateTo.value = dateFrom.value;
                }
            } else {
                if (btnRange)  { btnRange.classList.add(...activeClasses); btnRange.classList.remove(...inactiveClasses); }
                if (btnSingle) { btnSingle.classList.remove(...activeClasses); btnSingle.classList.add(...inactiveClasses); }

                if (boxRange)  boxRange.classList.remove('hidden');
                if (boxSingle) boxSingle.classList.add('hidden');
            }
            checkModalFilterValidation();
        };

        window.syncActSingleDate = function(val) {
            const dateFrom = document.getElementById('activity_date_from');
            const dateTo   = document.getElementById('activity_date_to');
            if (dateFrom) dateFrom.value = val;
            if (dateTo)   dateTo.value   = val;
            checkModalFilterValidation();
        };

        window.refreshArchiveDateModes = function() {
            const uploadFrom = document.getElementById('date_from')?.value || '';
            const uploadTo   = document.getElementById('date_to')?.value || '';
            if (uploadFrom && uploadTo && uploadFrom !== uploadTo) {
                setUploadDateMode('range');
            } else {
                setUploadDateMode('single');
                if (uploadFrom) {
                    const uploadSingle = document.getElementById('upload_date_single');
                    if (uploadSingle) uploadSingle.value = uploadFrom;
                }
            }

            const actFrom = document.getElementById('activity_date_from')?.value || '';
            const actTo   = document.getElementById('activity_date_to')?.value || '';
            if (actFrom && actTo && actFrom !== actTo) {
                setActDateMode('range');
            } else {
                setActDateMode('single');
                if (actFrom) {
                    const actSingle = document.getElementById('act_date_single');
                    if (actSingle) actSingle.value = actFrom;
                }
            }
        };

        // Inisialisasi Mode Tanggal berdasarkan Request URL
        refreshArchiveDateModes();

        function checkModalFilterValidation() {
            const btnApply = document.getElementById('btn-apply-modal');
            const msgDate  = document.getElementById('date-validation-msg');
            const msgAct   = document.getElementById('act-date-validation-msg');
            if (!btnApply) return;

            const dateFrom    = (document.getElementById('date_from')?.value || '').trim();
            const dateTo      = (document.getElementById('date_to')?.value || '').trim();
            const actDateFrom = (document.getElementById('activity_date_from')?.value || '').trim();
            const actDateTo   = (document.getElementById('activity_date_to')?.value || '').trim();

            const singleUploadDate      = (dateFrom && !dateTo) || (!dateFrom && dateTo);
            const rangeSameUploadDate   = (currentUploadDateMode === 'range' && dateFrom && dateTo && dateFrom === dateTo);
            const rangeBeforeUploadDate = (currentUploadDateMode === 'range' && dateFrom && dateTo && dateTo < dateFrom);

            const singleActDate      = (actDateFrom && !actDateTo) || (!actDateFrom && actDateTo);
            const rangeSameActDate   = (currentActDateMode === 'range' && actDateFrom && actDateTo && actDateFrom === actDateTo);
            const rangeBeforeActDate = (currentActDateMode === 'range' && actDateFrom && actDateTo && actDateTo < actDateFrom);

            if (msgDate) {
                if (singleUploadDate) {
                    msgDate.textContent = '* Wajib memasukkan Tanggal Unggah Dari dan Sampai pada mode Rentang.';
                    msgDate.classList.remove('hidden');
                } else if (rangeBeforeUploadDate) {
                    msgDate.textContent = '* Tanggal Unggah Sampai tidak boleh sebelum Tanggal Unggah Dari.';
                    msgDate.classList.remove('hidden');
                } else if (rangeSameUploadDate) {
                    msgDate.textContent = '* Mode Rentang Tanggal Unggah membutuhkan tanggal yang berbeda. Gunakan mode "1 Hari" untuk tanggal sama.';
                    msgDate.classList.remove('hidden');
                } else {
                    msgDate.classList.add('hidden');
                }
            }

            if (msgAct) {
                if (singleActDate) {
                    msgAct.textContent = '* Wajib memasukkan Tanggal Kegiatan Dari dan Sampai pada mode Rentang.';
                    msgAct.classList.remove('hidden');
                } else if (rangeBeforeActDate) {
                    msgAct.textContent = '* Tanggal Kegiatan Sampai tidak boleh sebelum Tanggal Kegiatan Dari.';
                    msgAct.classList.remove('hidden');
                } else if (rangeSameActDate) {
                    msgAct.textContent = '* Mode Rentang Tanggal Kegiatan membutuhkan tanggal yang berbeda. Gunakan mode "1 Hari" untuk tanggal sama.';
                    msgAct.classList.remove('hidden');
                } else {
                    msgAct.classList.add('hidden');
                }
            }

            const hasInvalidDates = singleUploadDate || rangeSameUploadDate || rangeBeforeUploadDate || singleActDate || rangeSameActDate || rangeBeforeActDate;
            const isFormValid = !hasInvalidDates && hasAnyAdvancedFilterValue();
            btnApply.disabled = !isFormValid;
        }

        // Global Event Delegation for modal inputs
        document.addEventListener('input', function (e) {
            if (e.target && e.target.closest('#advanced-filter-modal')) {
                checkModalFilterValidation();
            }
        });
        document.addEventListener('change', function (e) {
            if (e.target && e.target.closest('#advanced-filter-modal')) {
                checkModalFilterValidation();
            }
        });

        function clearAdvancedFilters() {
            const fields = ['date_from', 'date_to', 'activity_date_from', 'activity_date_to', 'upload_date_single', 'act_date_single'];
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            const formatEl = document.getElementById('file_format');
            if (formatEl) formatEl.value = '';

            const sortEl = document.getElementById('sort');
            if (sortEl) sortEl.value = 'newest';

            setUploadDateMode('single');
            setActDateMode('single');
            checkModalFilterValidation();
        }

        window.openModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                if (window.SIAPTIKA_GSAP && window.SIAPTIKA_GSAP.animateModal) {
                    window.SIAPTIKA_GSAP.animateModal(id);
                }
                document.body.style.overflow = 'hidden';
                if (id === 'advanced-filter-modal') {
                    refreshArchiveDateModes();
                    checkModalFilterValidation();
                }
            }
        };

        window.closeModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                if (modal.dataset.locked === '1') return;
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        window.handleBackdropClick = function(e, id) {
            const modal = document.getElementById(id);
            if (modal && modal.dataset.locked === '1') return;
            if (e.target === e.currentTarget) closeModal(id);
        };

        window.clearSearchInput = function() {
            if (searchInput) {
                searchInput.value = '';
                clearBtn.classList.add('hidden');
                doSearch();
            }
        };

        window.applyModalFilter = function() {
            const checkedRadio = form.querySelector('.filter-radio:checked');
            if (checkedRadio) checkedRadio.checked = false;

            const btnAdv = document.getElementById('btn-advanced');
            if (btnAdv) {
                btnAdv.classList.toggle('active', hasAnyAdvancedFilterValue());
            }

            closeModal('advanced-filter-modal');
            doSearch();
        };

        function doSearch() {
            const params = getParams();
            const url    = baseUrl + (params.toString() ? '?' + params.toString() : '');
            history.pushState(null, '', url);

            if (currentFetch) currentFetch.abort();
            const controller = new AbortController();
            currentFetch = controller;
            resultsBox.style.opacity = '0.5';

            if (typeof showGlobalLoading === 'function') showGlobalLoading('Memuat arsip…');

            const timeoutId = setTimeout(() => controller.abort('timeout'), 10000);

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal
            })
            .then(r => {
                clearTimeout(timeoutId);
                if (!r.ok) throw new Error('Server HTTP ' + r.status);
                return r.text();
            })
            .then(html => {
                resultsBox.innerHTML = html;
                resultsBox.style.opacity = '1';
                if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
                currentFetch = null;
            })
            .catch(err => {
                clearTimeout(timeoutId);
                if (err.name === 'AbortError' && err.message !== 'timeout') return;
                resultsBox.style.opacity = '1';
                if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
                currentFetch = null;

                var msg = (err.message === 'timeout')
                    ? 'Pencarian memakan waktu terlalu lama. Silakan coba lagi.'
                    : 'Gagal memuat data arsip. Periksa koneksi internet Anda.';

                resultsBox.innerHTML = '<div class="card-serif p-8 text-center my-6 flex flex-col items-center gap-3">' +
                    '<div class="w-10 h-10 rounded-full bg-red-50 border border-red-200 flex items-center justify-center text-red-600 mb-1">' +
                        '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>' +
                    '</div>' +
                    '<p class="text-sm font-medium text-[#1A1A1A]">' + msg + '</p>' +
                    '<button type="button" onclick="window.location.reload()" class="btn-secondary text-xs px-4 py-2 inline-flex items-center gap-2 mt-1">' +
                        '<svg class="w-4 h-4 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>' +
                        'Coba Lagi' +
                    '</button>' +
                '</div>';
            });
        }

        searchInput.addEventListener('input', function () {
            clearBtn.classList.toggle('hidden', !this.value);
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(doSearch, 350);
        });

        form.querySelectorAll('.filter-radio').forEach(function (radio) {
            radio.addEventListener('change', function () {
                clearAdvancedFilters();
                const btnAdv = document.getElementById('btn-advanced');
                if (btnAdv) btnAdv.classList.remove('active');
                clearTimeout(debounceTimer);
                doSearch();
            });
        });

        document.querySelectorAll('.adv-filter-field').forEach(function (field) {
            field.addEventListener('input', checkModalFilterValidation);
            field.addEventListener('change', checkModalFilterValidation);
        });

        resultsBox.addEventListener('click', function (e) {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            if (!href) return;
            const linkUrl = new URL(href, window.location.origin);
            if (!linkUrl.searchParams.has('page')) return;
            if (!href.includes('/admin/arsip')) return;

            e.preventDefault();
            history.pushState(null, '', href);
            resultsBox.style.opacity = '0.5';
            if (typeof showGlobalLoading === 'function') showGlobalLoading('Memuat halaman...');

            fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                resultsBox.innerHTML = html;
                resultsBox.style.opacity = '1';
                if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(() => { if (typeof hideGlobalLoading === 'function') hideGlobalLoading(); });
        });

        // ── Helper: Lock / Unlock Modal ────────────────────────────────
        window.lockModalForm = function(modalId) {
            var modal = document.getElementById(modalId);
            if (!modal) return;
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
        };

        window.unlockModalForm = function(modalId) {
            var modal = document.getElementById(modalId);
            if (!modal) return;
            modal.querySelectorAll('button').forEach(function (el) {
                if (el.dataset.wasDisabled !== '1') el.disabled = false;
                delete el.dataset.wasDisabled;
            });
            var ol = document.getElementById(modalId + '-lock-overlay');
            if (ol) ol.remove();
            modal.dataset.locked = '0';
        };

        // ── Edit Document Modal Helpers ──────────────────────────────
        window.openEditDocumentModal = function(id, type, filename, actionUrl) {
            var form = document.getElementById('edit-document-form');
            var hiddenType = document.getElementById('edit_document_type');
            var badge = document.getElementById('edit-doc-type-badge');
            var nameDisplay = document.getElementById('edit-current-filename');

            form.action = actionUrl;
            hiddenType.value = type;
            badge.textContent = type;
            if (nameDisplay) nameDisplay.textContent = filename;

            var extEl = document.getElementById('edit-file-ext');
            if (extEl) {
                var ext = filename.split('.').pop().toUpperCase();
                extEl.textContent = ext.length <= 4 ? ext : 'FILE';
            }

            cancelNewFileSelection();

            document.getElementById('edit-document-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.closeEditDocumentModal = function() {
            var modal = document.getElementById('edit-document-modal');
            if (modal && modal.dataset.locked === '1') return;
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        };

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

        window.applyEditFile = function(file) {
            var editBtn     = document.getElementById('btn-submit-edit-doc');
            var editZone    = document.getElementById('edit-drop-zone');
            var newCard     = document.getElementById('edit-new-file-card');
            var statusBadge = document.getElementById('new-file-status-badge');
            var newNameEl   = document.getElementById('edit-new-filename');
            var newSizeEl   = document.getElementById('edit-new-filesize');
            var newExtEl    = document.getElementById('edit-new-file-ext');

            if (!file) {
                cancelNewFileSelection();
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
            if (newSizeEl) newSizeEl.textContent = 'Ukuran: ' + (file.size / (1024 * 1024)).toFixed(2) + ' MB';
            if (newExtEl) {
                var ext = file.name.split('.').pop().toUpperCase();
                newExtEl.textContent = ext.length <= 4 ? ext : 'FILE';
            }

            if (statusBadge) {
                statusBadge.textContent = 'Berkas Baru Terpilih';
                statusBadge.className = 'text-[0.62rem] font-mono px-2 py-0.5 rounded border bg-amber-50 text-[#B8860B] border-amber-200 font-semibold';
            }
        };

        // Attach listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            var editZone  = document.getElementById('edit-drop-zone');
            var editInput = document.getElementById('edit_upload_file');

            if (editZone && editInput) {
                editZone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    editZone.classList.add('border-[#B8860B]', 'bg-[#FAFAF8]');
                    editZone.classList.remove('border-[#E8E4DF]');
                });
                editZone.addEventListener('dragleave', function (e) {
                    if (!editZone.contains(e.relatedTarget)) {
                        editZone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                        editZone.classList.add('border-[#E8E4DF]');
                    }
                });
                editZone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    editZone.classList.remove('border-[#B8860B]', 'bg-[#FAFAF8]');
                    editZone.classList.add('border-[#E8E4DF]');
                    var file = e.dataTransfer.files[0];
                    if (file) {
                        var dt = new DataTransfer();
                        dt.items.add(file);
                        editInput.files = dt.files;
                        applyEditFile(file);
                    }
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

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    var editModal = document.getElementById('edit-document-modal');
                    if (editModal && editModal.dataset.locked !== '1') closeEditDocumentModal();
                    var filterModal = document.getElementById('advanced-filter-modal');
                    if (filterModal && filterModal.dataset.locked !== '1') closeModal('advanced-filter-modal');
                }
            });
        });
    })();
    </script>

</x-layouts.admin>
