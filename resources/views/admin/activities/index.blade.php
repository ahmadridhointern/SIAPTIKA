<x-layouts.admin title="Daftar Kegiatan">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif text-4xl md:text-5xl text-[#1A1A1A] tracking-tight">
                    Jadwal Kegiatan
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Kelola, pantau, dan publikasikan seluruh kegiatan Bidang APTIKA.
                </p>
            </div>
            <div class="flex-shrink-0">
                <button onclick="openModal('create-modal')" class="btn-primary">
                    + Tambah Kegiatan
                </button>
            </div>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="card-serif p-5 mb-6 bg-[#FFFFFF]">
        <form id="filter-form" method="GET" action="{{ route('admin.activities.index') }}" onsubmit="event.preventDefault();"
              class="flex flex-col md:flex-row gap-3 items-stretch md:items-end">

            {{-- Search --}}
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
                    <input id="search" type="text" name="search" value="{{ request('search') }}"
                           placeholder="Judul atau tempat kegiatan..."
                           class="input-serif" style="padding-left: 2.5rem; padding-right: 2.5rem;" autocomplete="off">
                    
                    {{-- Clear Search Button --}}
                    <button type="button" id="clear-search" onclick="clearSearchInput()"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-[#6B6B6B] hover:text-[#B8860B] transition-colors {{ request('search') ? '' : 'hidden' }}"
                            style="background:none; border:none; cursor:pointer;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Status Filter (Segmented Radio Buttons) --}}
            <div class="w-full md:w-auto flex-shrink-0">
                <span class="block mb-2 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Status</span>
                <div class="flex items-center gap-2" style="min-height: 3rem;">
                    
                    {{-- Semua --}}
                    <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                        <input type="radio" name="status" value="" {{ request('status') === null || request('status') === '' ? 'checked' : '' }} class="hidden-radio">
                        <span class="status-radio-label">Semua</span>
                    </label>

                    {{-- Direncana --}}
                    <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                        <input type="radio" name="status" value="scheduled" {{ request('status') === 'scheduled' ? 'checked' : '' }} class="hidden-radio">
                        <span class="status-radio-label">Direncana</span>
                    </label>

                    {{-- Selesai --}}
                    <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                        <input type="radio" name="status" value="completed" {{ request('status') === 'completed' ? 'checked' : '' }} class="hidden-radio">
                        <span class="status-radio-label">Selesai</span>
                    </label>

                </div>
            </div>
        </form>
    </div>

    {{-- Container untuk AJAX Live Search --}}
    <div id="activities-container">
        @include('admin.activities.partials.list')
    </div>

    {{-- ================================================================
         CREATE MODAL
         ================================================================ --}}
    <div id="create-modal" class="modal-backdrop hidden" onclick="handleBackdropClick(event, 'create-modal')">
        <div class="modal-box">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Tambah Kegiatan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
                </div>
                <button type="button" onclick="closeModal('create-modal')"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('admin.activities.store') }}" novalidate class="flex flex-col overflow-hidden min-h-0 flex-1">
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-5 modal-form-body">
                    @csrf
                    <input type="hidden" name="_modal" value="create">

                    {{-- Validation Errors --}}
                    @if($errors->any() && old('_modal') === 'create')
                        <x-validation-errors class="mb-5" />
                    @endif

                    <div class="space-y-5">
                        {{-- Judul --}}
                        <div>
                            <label for="c-title" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input id="c-title" type="text" name="title" value="{{ old('_modal') === 'create' ? old('title') : '' }}"
                                   maxlength="255" placeholder="Contoh: Rapat Evaluasi Smart City Semester I"
                                   class="input-serif @if($errors->has('title') && old('_modal') === 'create') border-red-400 bg-red-50 @endif"
                                   autocomplete="off">
                            @if($errors->has('title') && old('_modal') === 'create')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('title') }}</p>
                            @else
                                <p class="mt-1 text-xs text-[#6B6B6B]">Minimal 5, maksimal 255 karakter.</p>
                            @endif
                        </div>

                        {{-- Grid Tanggal & Waktu --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="c-date" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tanggal <span class="text-red-500">*</span></label>
                                <input id="c-date" type="date" name="activity_date"
                                       value="{{ old('_modal') === 'create' ? old('activity_date') : '' }}"
                                       onchange="syncStatusFromDate('c-date','c-time','c-status','c-status-display')"
                                       class="input-serif @if($errors->has('activity_date') && old('_modal') === 'create') border-red-400 bg-red-50 @endif">
                                @if($errors->has('activity_date') && old('_modal') === 'create')
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('activity_date') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="c-time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Waktu <span class="text-red-500">*</span></label>
                                <input id="c-time" type="time" name="time"
                                       value="{{ old('_modal') === 'create' ? old('time') : '' }}"
                                       onchange="syncStatusFromDate('c-date','c-time','c-status','c-status-display')"
                                       class="input-serif @if($errors->has('time') && old('_modal') === 'create') border-red-400 bg-red-50 @endif">
                                @if($errors->has('time') && old('_modal') === 'create')
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('time') }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Tempat --}}
                        <div>
                            <label for="c-location" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tempat <span class="text-red-500">*</span></label>
                            <input id="c-location" type="text" name="location"
                                   value="{{ old('_modal') === 'create' ? old('location') : '' }}"
                                   maxlength="255" placeholder="Contoh: Ruang Rapat Bidang APTIKA Lt. 3"
                                   class="input-serif @if($errors->has('location') && old('_modal') === 'create') border-red-400 bg-red-50 @endif"
                                   autocomplete="off">
                            @if($errors->has('location') && old('_modal') === 'create')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('location') }}</p>
                            @endif
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Status</label>
                            <input type="hidden" id="c-status" name="status" value="{{ old('_modal') === 'create' ? old('status') : '' }}">
                            <input type="text" id="c-status-display" class="input-serif cursor-not-allowed" disabled readonly
                                   value="Silakan masukkan tanggal dan waktu kegiatan terlebih dahulu"
                                   style="min-height: 3rem; background-color: #F5F3F0 !important; color: #888888; opacity: 1; user-select: none;">
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="c-description" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                                Deskripsi <span class="text-xs font-normal text-[#6B6B6B]">(opsional)</span>
                            </label>
                            <textarea id="c-description" name="description" rows="4" maxlength="2000"
                                      placeholder="Agenda, catatan, atau detail kegiatan..."
                                      class="input-serif @if($errors->has('description') && old('_modal') === 'create') border-red-400 bg-red-50 @endif"
                                      style="height: auto; padding-top: 0.75rem; padding-bottom: 0.75rem;">{{ old('_modal') === 'create' ? old('description') : '' }}</textarea>
                            @if($errors->has('description') && old('_modal') === 'create')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button type="submit" class="btn-primary">Simpan Kegiatan</button>
                    <button type="button" onclick="closeModal('create-modal')"
                            class="btn-secondary">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================
         EDIT MODAL
         ================================================================ --}}
    @php
        $editModalId = $editActivity?->id ?? (old('_modal') === 'edit' ? old('_activity_id') : null);
        $editFormAction = $editModalId ? url("admin/activities/{$editModalId}") : '#';
    @endphp

    <div id="edit-modal" class="modal-backdrop hidden" onclick="handleBackdropClick(event, 'edit-modal')">
        <div class="modal-box">
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Ubah Kegiatan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Kolom bertanda <span class="text-red-500">*</span> wajib diisi.</p>
                </div>
                <button type="button" onclick="closeModal('edit-modal')"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="edit-form" method="POST" action="{{ $editFormAction }}" novalidate class="flex flex-col overflow-hidden min-h-0 flex-1">
                <div class="flex-1 overflow-y-auto px-8 py-6 space-y-5 modal-form-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_modal" value="edit">
                    <input type="hidden" id="edit-activity-id" name="_activity_id" value="{{ $editModalId }}">

                    {{-- Validation Errors --}}
                    @if($errors->any() && old('_modal') === 'edit')
                        <x-validation-errors class="mb-5" />
                    @endif

                    <div class="space-y-5">
                        <div>
                            <label for="e-title" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input id="e-title" type="text" name="title"
                                   value="{{ old('_modal') === 'edit' ? old('title') : ($editActivity?->title ?? '') }}"
                                   maxlength="255" placeholder="Contoh: Rapat Evaluasi Smart City Semester I"
                                   class="input-serif @if($errors->has('title') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif"
                                   autocomplete="off">
                            @if($errors->has('title') && old('_modal') === 'edit')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('title') }}</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="e-date" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tanggal <span class="text-red-500">*</span></label>
                                <input id="e-date" type="date" name="activity_date"
                                       value="{{ old('_modal') === 'edit' ? old('activity_date') : ($editActivity?->activity_date->toDateString() ?? '') }}"
                                       onchange="syncStatusFromDate('e-date','e-time','e-status','e-status-display')"
                                       class="input-serif @if($errors->has('activity_date') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif">
                                @if($errors->has('activity_date') && old('_modal') === 'edit')
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('activity_date') }}</p>
                                @endif
                            </div>
                            <div>
                                <label for="e-time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Waktu <span class="text-red-500">*</span></label>
                                <input id="e-time" type="time" name="time"
                                       value="{{ old('_modal') === 'edit' ? old('time') : ($editActivity ? \Carbon\Carbon::parse($editActivity->time)->format('H:i') : '') }}"
                                       onchange="syncStatusFromDate('e-date','e-time','e-status','e-status-display')"
                                       class="input-serif @if($errors->has('time') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif">
                                @if($errors->has('time') && old('_modal') === 'edit')
                                    <p class="mt-1 text-xs text-red-600">{{ $errors->first('time') }}</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label for="e-location" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Tempat <span class="text-red-500">*</span></label>
                            <input id="e-location" type="text" name="location"
                                   value="{{ old('_modal') === 'edit' ? old('location') : ($editActivity?->location ?? '') }}"
                                   maxlength="255" placeholder="Contoh: Ruang Rapat Bidang APTIKA Lt. 3"
                                   class="input-serif @if($errors->has('location') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif"
                                   autocomplete="off">
                            @if($errors->has('location') && old('_modal') === 'edit')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('location') }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Status</label>
                            <input type="hidden" id="e-status" name="status" value="{{ old('_modal') === 'edit' ? old('status') : ($editActivity?->status ?? '') }}">
                            <input type="text" id="e-status-display" class="input-serif cursor-not-allowed" disabled readonly
                                   value="Silakan masukkan tanggal dan waktu kegiatan terlebih dahulu"
                                   style="min-height: 3rem; background-color: #F5F3F0 !important; color: #888888; opacity: 1; user-select: none;">
                        </div>

                        <div>
                            <label for="e-description" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                                Deskripsi <span class="text-xs font-normal text-[#6B6B6B]">(opsional)</span>
                            </label>
                            <textarea id="e-description" name="description" rows="4" maxlength="2000"
                                      placeholder="Agenda, catatan, atau detail kegiatan..."
                                      class="input-serif @if($errors->has('description') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif"
                                      style="height: auto; padding-top: 0.75rem; padding-bottom: 0.75rem;">{{ old('_modal') === 'edit' ? old('description') : ($editActivity?->description ?? '') }}</textarea>
                            @if($errors->has('description') && old('_modal') === 'edit')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button type="submit" class="btn-primary">Perbarui Kegiatan</button>
                    <button type="button" onclick="closeModal('edit-modal')"
                            class="btn-secondary">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================
         DELETE MODAL
         ================================================================ --}}
    <div id="delete-modal" class="modal-backdrop hidden" onclick="handleBackdropClick(event, 'delete-modal')">
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
                            Anda akan menghapus kegiatan
                            <strong id="delete-activity-name" class="text-[#1A1A1A]"></strong>.
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>

                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="flex-1 text-center text-xs font-mono font-semibold py-2.5 px-4 rounded border border-red-300 text-red-600 bg-red-50 hover:bg-red-100 transition-all duration-150 cursor-pointer">
                            Ya, Hapus
                        </button>
                        <button type="button" onclick="closeModal('delete-modal')"
                                class="btn-secondary flex-1">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Helper: URL base for JS --}}
    <input type="hidden" id="activities-url-base" value="{{ url('/admin/activities') }}">

    {{-- ================================================================
         JAVASCRIPT
         ================================================================ --}}
    <script>
    // ── Helpers ────────────────────────────────────────────────────────
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }
    function handleBackdropClick(e, id) {
        if (e.target === e.currentTarget) closeModal(id);
    }
    // ESC key closes any open modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['create-modal','edit-modal','delete-modal'].forEach(closeModal);
        }
    });

    // ── Open Edit Modal (from "Ubah" button) ──────────────────────────
    function openEditModal(btn) {
        const d = btn.dataset;
        const baseUrl = document.getElementById('activities-url-base').value;
        // Set form action to /admin/activities/{id} (PUT)
        document.getElementById('edit-form').action = baseUrl + '/' + d.id;
        document.getElementById('edit-activity-id').value = d.id;
        // Populate fields
        document.getElementById('e-title').value       = d.title;
        document.getElementById('e-date').value        = d.date;
        document.getElementById('e-time').value        = d.time;
        document.getElementById('e-location').value    = d.location;
        document.getElementById('e-description').value = d.description;
        document.getElementById('e-status').value      = d.status;
        // Sync status lock
        syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
        openModal('edit-modal');
    }

    // ── Open Delete Modal ─────────────────────────────────────────────
    function openDeleteModal(id, title) {
        const baseUrl = document.getElementById('activities-url-base').value;
        document.getElementById('delete-form').action = baseUrl + '/' + id;
        document.getElementById('delete-activity-name').textContent = '"' + title + '"';
        openModal('delete-modal');
    }

    // ── Auto-set Status when Past/Future Date ───────────────────────
    function syncStatusFromDate(dateId, timeId, statusId, displayId) {
        const dateEl   = document.getElementById(dateId);
        const timeEl   = document.getElementById(timeId);
        const statusEl = document.getElementById(statusId);
        const displayEl = document.getElementById(displayId);
        if (!dateEl || !timeEl || !statusEl || !displayEl) return;

        if (!dateEl.value || !timeEl.value) {
            statusEl.value = '';
            displayEl.value = 'Silakan masukkan tanggal dan waktu kegiatan terlebih dahulu';
            displayEl.classList.add('text-red-600');
            displayEl.classList.remove('text-[#888888]');
            return;
        }

        displayEl.classList.remove('text-red-600');
        displayEl.classList.add('text-[#888888]');

        // Compare full datetime (including hour/minute)
        const selected = new Date(dateEl.value + 'T' + timeEl.value);
        const now      = new Date();

        if (selected > now) {
            statusEl.value = 'scheduled';
            displayEl.value = 'Direncana (Waktu kegiatan belum mulai)';
        } else {
            statusEl.value = 'completed';
            displayEl.value = 'Selesai (Waktu kegiatan sudah lewat)';
        }
    }

    // ── AJAX Live Search, Filter, and Pagination ───────────────────
    let searchTimer;

    // Helper to get checked radio value
    function getSelectedStatus() {
        const checkedRadio = document.querySelector('input[name="status"]:checked');
        return checkedRadio ? checkedRadio.value : '';
    }

    function fetchActivities(page = null) {
        const searchInput  = document.getElementById('search');
        const container    = document.getElementById('activities-container');
        if (!container) return;

        // Build query string
        const params = new URLSearchParams();
        if (page) {
            params.append('page', page);
        }
        if (searchInput && searchInput.value.trim() !== '') {
            params.append('search', searchInput.value.trim());
        }
        
        const statusVal = getSelectedStatus();
        if (statusVal !== '') {
            params.append('status', statusVal);
        }

        const url = '{{ route("admin.activities.index") }}?' + params.toString();

        // Update browser URL without reload
        window.history.pushState({}, '', url);

        // Fetch partial view HTML
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            container.innerHTML = html;
        })
        .catch(err => console.error('Error fetching activities:', err));
    }

    function toggleClearSearchButton() {
        const searchInput = document.getElementById('search');
        const clearBtn = document.getElementById('clear-search');
        if (!clearBtn) return;
        if (searchInput && searchInput.value.trim() !== '') {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    function clearSearchInput() {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.value = '';
            toggleClearSearchButton();
            fetchActivities();
        }
    }

    // Set up listeners for live input and status select
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput  = document.getElementById('search');

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimer);
                toggleClearSearchButton();
                searchTimer = setTimeout(function () {
                    fetchActivities();
                }, 600);
            });
        }

        // Listen for change on status radio buttons
        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                fetchActivities();
            });
        });

        // Handle AJAX Pagination clicks
        const container = document.getElementById('activities-container');
        if (container) {
            container.addEventListener('click', function (e) {
                const target = e.target.closest('.pagination a, a[rel="next"], a[rel="prev"]');
                if (target) {
                    e.preventDefault();
                    const url = new URL(target.href);
                    const page = url.searchParams.get('page');
                    fetchActivities(page);
                    // Smooth scroll to top of index content
                    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }
    });

    // ── Auto-open Modals on Validation Error or ?create/?edit query ──
    document.addEventListener('DOMContentLoaded', function () {

        @if($errors->any() && old('_modal') === 'create')
            openModal('create-modal');
            // Sync date lock on re-open
            syncStatusFromDate('c-date', 'c-time', 'c-status', 'c-status-display');
        @endif

        @if($errors->any() && old('_modal') === 'edit')
            // Edit modal - form already has old() values from PHP, just open it
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
        @endif

        @if($editActivity)
            // Triggered from show page ?edit=id link
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
        @endif

        @if(request()->has('create'))
            openModal('create-modal');
        @endif

        // Apply initial date lock check for any open modals
        syncStatusFromDate('c-date', 'c-time', 'c-status', 'c-status-display');
        syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
    });
    </script>

</x-layouts.admin>
