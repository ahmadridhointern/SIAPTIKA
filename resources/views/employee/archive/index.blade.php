<x-layouts.employee title="Arsip Dokumen">

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
                Daftar seluruh berkas dokumen arsip kegiatan Bidang APTIKA, Diskominfotik Riau.
            </p>
        </div>
    </div>

    @php
        $hasAdvFilter = request()->filled('date_from') || request()->filled('date_to') || request()->filled('activity_date_from') || request()->filled('activity_date_to') || (request()->filled('sort') && request('sort') !== 'newest');
    @endphp

    {{-- Search & Filter Bar --}}
    <div class="card-serif mb-6 bg-[#FFFFFF]">
        <form id="filter-form" method="GET" action="{{ route('employee.archive.index') }}" onsubmit="event.preventDefault();"
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
        @include('employee.archive.partials.list')
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

                {{-- Urutkan --}}
                <div>
                    <label for="sort" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Urutkan Berdasarkan</label>
                    <select id="sort" name="sort" class="sort-select adv-filter-field">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Baru Ditambahkan (Terbaru Diunggah)</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Diunggah Terlama</option>
                        <option value="activity_date" {{ request('sort') === 'activity_date' ? 'selected' : '' }}>Tanggal Kegiatan Terbaru</option>
                        <option value="az" {{ request('sort') === 'az' ? 'selected' : '' }}>Nama Berkas (A → Z)</option>
                    </select>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-3 px-8 py-4 bg-[#FAFAF8] border-t border-[#E8E4DF] w-full">
                <button type="button" onclick="closeModal('advanced-filter-modal')" class="btn-secondary" style="min-height: 2.5rem; padding: 0 1.25rem;">
                    Batal
                </button>
                <button type="button" id="btn-apply-modal" onclick="applyAdvancedFilter()" disabled class="btn-primary opacity-50 cursor-not-allowed" style="min-height: 2.5rem; padding: 0 1.25rem;">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </div>
    @endpush

    {{-- Live Search & Filter Script --}}
    <script>
    if (typeof window.openModal !== 'function') {
        window.openModal = function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const appContent = document.getElementById('app-content');
                if (appContent) appContent.classList.add('modal-open-filter');
                if (id === 'advanced-filter-modal') {
                    if (typeof refreshArchiveDateModes === 'function') refreshArchiveDateModes();
                    if (typeof checkModalFilterValidation === 'function') checkModalFilterValidation();
                }
            }
        };
        window.closeModal = function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
                const anyOpen = Array.from(document.querySelectorAll('.modal-backdrop')).some(m => !m.classList.contains('hidden'));
                if (!anyOpen) {
                    document.body.style.overflow = '';
                    const appContent = document.getElementById('app-content');
                    if (appContent) appContent.classList.remove('modal-open-filter');
                }
            }
        };
        window.handleBackdropClick = function(e, id) {
            if (e.target.id === id) closeModal(id);
        };
    }

    (function () {
        const form        = document.getElementById('filter-form');
        const searchInput = document.getElementById('search');
        const clearBtn    = document.getElementById('clear-search');
        const resultsBox  = document.getElementById('archive-results');
        const baseUrl     = '{{ route('employee.archive.index') }}';

        let debounceTimer = null;
        let currentFetch  = null;

        function hasAnyAdvancedFilterValue() {
            const format      = document.getElementById('file_format')?.value;
            const dateFrom    = (document.getElementById('date_from')?.value || '').trim();
            const dateTo      = (document.getElementById('date_to')?.value || '').trim();
            const actDateFrom = (document.getElementById('activity_date_from')?.value || '').trim();
            const actDateTo   = (document.getElementById('activity_date_to')?.value || '').trim();
            const sort        = document.getElementById('sort')?.value;

            const hasBothUploadDates   = !!(dateFrom && dateTo);
            const hasBothActivityDates = !!(actDateFrom && actDateTo);

            if (format) return true;
            if (hasBothUploadDates) return true;
            if (hasBothActivityDates) return true;
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
            const msgEl1   = document.getElementById('date-validation-msg');
            const msgEl2   = document.getElementById('act-date-validation-msg');
            if (!btnApply) return;

            const dateFrom    = (document.getElementById('date_from')?.value || '').trim();
            const dateTo      = (document.getElementById('date_to')?.value || '').trim();
            const actDateFrom = (document.getElementById('activity_date_from')?.value || '').trim();
            const actDateTo   = (document.getElementById('activity_date_to')?.value || '').trim();

            const singleUploadDate      = (dateFrom && !dateTo) || (!dateFrom && dateTo);
            const rangeSameUploadDate   = (currentUploadDateMode === 'range' && dateFrom && dateTo && dateFrom === dateTo);
            const rangeBeforeUploadDate = (currentUploadDateMode === 'range' && dateFrom && dateTo && dateTo < dateFrom);

            const singleActivityDate  = (actDateFrom && !actDateTo) || (!actDateFrom && actDateTo);
            const rangeSameActDate    = (currentActDateMode === 'range' && actDateFrom && actDateTo && actDateFrom === actDateTo);
            const rangeBeforeActDate  = (currentActDateMode === 'range' && actDateFrom && actDateTo && actDateTo < actDateFrom);

            if (msgEl1) {
                if (singleUploadDate) {
                    msgEl1.textContent = '* Isi tanggal unggah dari dan sampai pada mode rentang.';
                    msgEl1.classList.remove('hidden');
                } else if (rangeBeforeUploadDate) {
                    msgEl1.textContent = '* Tanggal unggah sampai tidak boleh sebelum tanggal unggah dari.';
                    msgEl1.classList.remove('hidden');
                } else if (rangeSameUploadDate) {
                    msgEl1.textContent = '* Pada mode rentang, tanggal unggah sampai tidak boleh sama dengan tanggal unggah dari. Gunakan mode 1 Hari untuk tanggal yang sama.';
                    msgEl1.classList.remove('hidden');
                } else {
                    msgEl1.classList.add('hidden');
                }
            }

            if (msgEl2) {
                if (singleActivityDate) {
                    msgEl2.textContent = '* Isi tanggal kegiatan dari dan sampai pada mode rentang.';
                    msgEl2.classList.remove('hidden');
                } else if (rangeBeforeActDate) {
                    msgEl2.textContent = '* Tanggal kegiatan sampai tidak boleh sebelum tanggal kegiatan dari.';
                    msgEl2.classList.remove('hidden');
                } else if (rangeSameActDate) {
                    msgEl2.textContent = '* Pada mode rentang, tanggal kegiatan sampai tidak boleh sama dengan tanggal kegiatan dari. Gunakan mode 1 Hari untuk tanggal yang sama.';
                    msgEl2.classList.remove('hidden');
                } else {
                    msgEl2.classList.add('hidden');
                }
            }

            const hasInvalidDates = singleUploadDate || rangeSameUploadDate || rangeBeforeUploadDate || singleActivityDate || rangeSameActDate || rangeBeforeActDate;
            const isValid = !hasInvalidDates && hasAnyAdvancedFilterValue();
            btnApply.disabled = !isValid;
            if (isValid) {
                btnApply.style.opacity = '1';
                btnApply.style.cursor = 'pointer';
                btnApply.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btnApply.style.opacity = '0.5';
                btnApply.style.cursor = 'not-allowed';
                btnApply.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }
        window.checkModalFilterValidation = checkModalFilterValidation;


        window.applyAdvancedFilter = function() {
            closeModal('advanced-filter-modal');
            doSearch();
        };

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

            const sort = document.getElementById('sort');
            if (sort) sort.value = 'newest';

            setUploadDateMode('single');
            setActDateMode('single');
            checkModalFilterValidation();
        }

        window.clearSearchInput = function () {
            searchInput.value = '';
            clearBtn.classList.add('hidden');
            doSearch();
            searchInput.focus();
        };

        function getParams() {
            const params = new URLSearchParams();
            const search = searchInput.value.trim();
            if (search) params.set('search', search);

            const checkedType = form.querySelector('.filter-radio:checked');
            if (checkedType && checkedType.value) params.set('type', checkedType.value);

            const format      = document.getElementById('file_format')?.value;
            const dateFrom    = document.getElementById('date_from')?.value;
            const dateTo      = document.getElementById('date_to')?.value;
            const actDateFrom = document.getElementById('activity_date_from')?.value;
            const actDateTo   = document.getElementById('activity_date_to')?.value;
            const sort        = document.getElementById('sort')?.value;

            if (format)      params.set('file_format', format);
            if (dateFrom)    params.set('date_from', dateFrom);
            if (dateTo)      params.set('date_to', dateTo);
            if (actDateFrom) params.set('activity_date_from', actDateFrom);
            if (actDateTo)   params.set('activity_date_to', actDateTo);
            if (sort && sort !== 'newest') params.set('sort', sort);

            return params;
        }

        function updateBadge() {
            const btnAdv = document.getElementById('btn-advanced');
            const isAdvActive = hasAnyAdvancedFilterValue();

            if (btnAdv) {
                if (isAdvActive) {
                    btnAdv.classList.add('active');
                    form.querySelectorAll('.filter-radio').forEach(r => r.checked = false);
                } else {
                    btnAdv.classList.remove('active');
                }
            }
            checkModalFilterValidation();
        }

        function doSearch() {
            updateBadge();
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
            if (!href.includes('/arsip')) return;

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

        updateBadge();
    })();
    </script>

</x-layouts.employee>
