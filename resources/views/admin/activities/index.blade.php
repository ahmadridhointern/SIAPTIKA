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

    @php
        $hasAdvFilter = request()->filled('date_from') || request()->filled('date_to') || request()->filled('location') || request()->filled('has_documents') || (request()->filled('sort') && request('sort') !== 'newest');
    @endphp

    {{-- Search & Filter Bar --}}
    <div class="card-serif mb-6 bg-[#FFFFFF]">
        <form id="filter-form" method="GET" action="{{ route('admin.activities.index') }}" onsubmit="event.preventDefault();"
              class="p-5">

            {{-- Baris 1: Search + Status + Tombol Filter Lanjutan --}}
            <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-end">

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
                                class="absolute inset-y-0 right-0 flex items-center text-[#6B6B6B] hover:text-[#B8860B] transition-colors {{ request('search') ? '' : 'hidden' }}"
                                style="background:none; border:none; cursor:pointer; padding: 0 0.75rem;">
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
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="status" value="" {{ !$hasAdvFilter && (request('status') === null || request('status') === '') ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Semua</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="status" value="scheduled" {{ !$hasAdvFilter && request('status') === 'scheduled' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Direncana</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="status" value="ongoing" {{ !$hasAdvFilter && request('status') === 'ongoing' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Berlangsung</span>
                        </label>
                        <label class="status-radio-btn flex items-center justify-center cursor-pointer select-none">
                            <input type="radio" name="status" value="completed" {{ !$hasAdvFilter && request('status') === 'completed' ? 'checked' : '' }} class="hidden-radio filter-radio">
                            <span class="status-radio-label">Selesai</span>
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


    {{-- Container untuk AJAX Live Search --}}
    <div id="activities-container">
        @include('admin.activities.partials.list')
    </div>


    @push('modals')
    {{-- ================================================================
         ADVANCED FILTER MODAL
         ================================================================ --}}
    <div id="advanced-filter-modal" class="modal-backdrop hidden" onclick="handleBackdropClick(event, 'advanced-filter-modal')">
        <div class="modal-box" style="max-width: 32rem;">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Filter Lanjutan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Filter spesifik kegiatan Bidang APTIKA.</p>
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
                {{-- Filter Tanggal (Compact Mode Switcher) --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Tanggal Kegiatan</label>
                        <div class="inline-flex p-0.5 bg-[#F5F3F0] rounded-lg border border-[#E8E4DF] text-[10px] font-mono gap-0.5" role="group">
                            <button type="button" id="btn-date-mode-single" onclick="setActivityDateMode('single')"
                                    class="px-2 py-0.5 rounded-md font-semibold transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                1 Hari
                            </button>
                            <button type="button" id="btn-date-mode-range" onclick="setActivityDateMode('range')"
                                    class="px-2 py-0.5 rounded-md font-medium text-[#6B6B6B] hover:text-[#1A1A1A] transition-all duration-150 cursor-pointer"
                                    style="border:none;">
                                Rentang
                            </button>
                        </div>
                    </div>

                    {{-- Mode 1: Satu Hari --}}
                    <div id="container-date-single">
                        <input type="date" id="date_single" onchange="syncSingleDateToRange(this.value)" class="date-input">
                    </div>

                    {{-- Mode 2: Rentang Tanggal --}}
                    <div id="container-date-range" class="hidden">
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
                    <p id="date-validation-msg" class="text-xs text-red-500 mt-1.5 hidden">* Wajib memasukkan Tanggal.</p>
                </div>


                {{-- Lokasi (Autocomplete Input + Datalist) --}}
                <div>
                    <label for="location" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Lokasi Kegiatan</label>
                    <input type="text" id="location" name="location" list="location-list" value="{{ request('location') }}"
                           placeholder="Ketik atau pilih lokasi kegiatan..."
                           class="input-serif adv-filter-field" autocomplete="off">
                    <datalist id="location-list">
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Urutkan --}}
                <div>
                    <label for="sort" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Urutkan Berdasarkan</label>
                    <select id="sort" name="sort" class="sort-select adv-filter-field">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Pelaksanaan Terbaru</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Pelaksanaan Terlama</option>
                        <option value="created_newest" {{ request('sort') === 'created_newest' ? 'selected' : '' }}>Baru Ditambahkan (Tanggal Dibuat)</option>
                        <option value="created_oldest" {{ request('sort') === 'created_oldest' ? 'selected' : '' }}>Dibuat Terlama (Tanggal Dibuat)</option>
                        <option value="az" {{ request('sort') === 'az' ? 'selected' : '' }}>Judul (A → Z)</option>
                    </select>
                </div>

                {{-- Has Documents --}}
                <div class="pt-1">
                    <label class="toggle-wrapper" title="Hanya kegiatan yang sudah memiliki arsip dokumen">
                        <input type="checkbox" name="has_documents" value="1" {{ request('has_documents') === '1' ? 'checked' : '' }} class="toggle-input adv-filter-field" id="has_documents">
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        <span class="toggle-label-text">Hanya tampilkan kegiatan dengan dokumen arsip</span>
                    </label>
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
            <form id="create-form" method="POST" action="{{ route('admin.activities.store') }}" novalidate class="flex flex-col overflow-hidden min-h-0 flex-1">
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
                    <button id="btn-create-submit" type="submit" class="btn-primary">Simpan Kegiatan</button>
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
                    <button id="btn-edit-submit" type="submit" class="btn-primary">Perbarui Kegiatan</button>
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
        <div class="modal-box">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-8 pt-7 pb-5 border-b border-[#E8E4DF]">
                <div>
                    <h2 class="font-serif text-2xl text-[#1A1A1A]">Hapus Kegiatan</h2>
                    <p class="text-xs text-[#6B6B6B] mt-0.5">Konfirmasi penghapusan data kegiatan dari sistem.</p>
                </div>
                <button type="button" onclick="closeModal('delete-modal')"
                        class="text-[#6B6B6B] hover:text-[#1A1A1A] transition-colors p-1 rounded"
                        style="background:none;border:none;cursor:pointer;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Body --}}
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')

                <div class="px-8 py-6 space-y-4">
                    <p class="text-sm text-[#1A1A1A] leading-relaxed mb-3">
                        Apakah Anda yakin ingin menghapus kegiatan <strong id="delete-activity-name" class="font-semibold text-[#1A1A1A]"></strong>?
                    </p>
                    <div class="p-3.5 mt-5 rounded-lg bg-red-50 border border-red-200 text-xs text-red-700 leading-relaxed font-mono">
                        ⚠️ Tindakan ini tidak dapat dibatalkan. Seluruh berkas dokumen terlampir pada kegiatan ini akan terhapus.
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center gap-4 px-8 py-5 border-t border-[#E8E4DF] bg-[#FAFAF8] flex-shrink-0">
                    <button id="btn-delete-submit" type="submit" class="btn-primary bg-[#DC2626] hover:bg-[#B91C1C] border-[#DC2626]">
                        Hapus Kegiatan
                    </button>
                    <button type="button" onclick="closeModal('delete-modal')" class="btn-secondary">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endpush

    {{-- Helper: URL base for JS --}}
    <input type="hidden" id="activities-url-base" value="{{ url('/admin/activities') }}">

    {{-- ================================================================
         JAVASCRIPT
         ================================================================ --}}
    <script>
    // ── Helpers ────────────────────────────────────────────────────────
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            const appContent = document.getElementById('app-content');
            if (appContent) {
                appContent.classList.add('modal-open-filter');
            }
            if (id === 'create-modal' && typeof validateCreateModalForm === 'function') {
                validateCreateModalForm();
            }
            if (id === 'advanced-filter-modal') {
                if (typeof refreshActivityDateModes === 'function') refreshActivityDateModes();
                if (typeof checkModalFilterValidation === 'function') checkModalFilterValidation();
            }

        }
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            
            // Check if any other modal is still open
            const anyOpen = Array.from(document.querySelectorAll('.modal-backdrop')).some(m => !m.classList.contains('hidden'));
            if (!anyOpen) {
                document.body.style.overflow = '';
                const appContent = document.getElementById('app-content');
                if (appContent) {
                    appContent.classList.remove('modal-open-filter');
                }
            }
        }
    }
    function handleBackdropClick(e, id) {
        // Jangan tutup modal jika mekanisme pengaman aktif (data-locked = '1')
        const modal = document.getElementById(id);
        if (modal && modal.dataset.locked === '1') return;
        if (e.target === e.currentTarget) closeModal(id);
    }
    // ESC key closes any open modal (kecuali yang sedang locked)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['create-modal','edit-modal','delete-modal'].forEach(function(id) {
                const modal = document.getElementById(id);
                if (modal && modal.dataset.locked === '1') return; // skip locked
                closeModal(id);
            });
        }
    });


    let editModalInitialState = {};

    function storeEditModalInitialState() {
        editModalInitialState = {
            title: document.getElementById('e-title')?.value || '',
            date: document.getElementById('e-date')?.value || '',
            time: document.getElementById('e-time')?.value || '',
            location: document.getElementById('e-location')?.value || '',
            description: document.getElementById('e-description')?.value || '',
            status: document.getElementById('e-status')?.value || ''
        };
        validateEditModalForm();
    }

    function validateEditModalForm() {
        const btn = document.getElementById('btn-edit-submit');
        if (!btn) return;
        const currentTitle = document.getElementById('e-title')?.value || '';
        const currentDate = document.getElementById('e-date')?.value || '';
        const currentTime = document.getElementById('e-time')?.value || '';
        const currentLocation = document.getElementById('e-location')?.value || '';
        const currentDescription = document.getElementById('e-description')?.value || '';
        const currentStatus = document.getElementById('e-status')?.value || '';

        const hasRequired = currentTitle.trim() !== '' && currentDate !== '' && currentTime !== '' && currentLocation.trim() !== '';
        const isChanged = currentTitle !== editModalInitialState.title ||
                          currentDate !== editModalInitialState.date ||
                          currentTime !== editModalInitialState.time ||
                          currentLocation !== editModalInitialState.location ||
                          currentDescription !== editModalInitialState.description ||
                          currentStatus !== editModalInitialState.status;

        btn.disabled = !(hasRequired && isChanged);

        if (!hasRequired) {
            btn.title = 'Lengkapi seluruh kolom wajib (Judul, Tanggal, Waktu, Tempat) terlebih dahulu';
        } else if (!isChanged) {
            btn.title = 'Ubah setidaknya satu data kegiatan untuk memperbarui';
        } else {
            btn.title = 'Simpan perubahan data kegiatan';
        }
    }

    function validateCreateModalForm() {
        const btn = document.getElementById('btn-create-submit');
        const title = document.getElementById('c-title');
        const date = document.getElementById('c-date');
        const time = document.getElementById('c-time');
        const location = document.getElementById('c-location');
        if (!btn || !title || !date || !time || !location) return;

        const isValid = title.value.trim() !== '' &&
                        date.value !== '' &&
                        time.value !== '' &&
                        location.value.trim() !== '';
        btn.disabled = !isValid;

        if (!isValid) {
            btn.title = 'Lengkapi seluruh kolom wajib (Judul, Tanggal, Waktu, Tempat) terlebih dahulu';
        } else {
            btn.title = 'Simpan kegiatan baru';
        }
    }

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
        storeEditModalInitialState();
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

        // Warna teks selalu seragam (#888888, normal)
        displayEl.style.color = '#888888';
        displayEl.style.fontWeight = 'normal';

        if (!dateEl.value || !timeEl.value) {
            statusEl.value = '';
            displayEl.value = 'Silakan masukkan tanggal dan waktu kegiatan terlebih dahulu';
            return;
        }

        // Compare full datetime (including hour/minute)
        const selected = new Date(dateEl.value + 'T' + timeEl.value);
        const now      = new Date();

        if (selected > now) {
            statusEl.value = 'scheduled';
            displayEl.value = 'Direncana (Kegiatan belum berlangsung)';
        } else {
            statusEl.value = 'completed';
            displayEl.value = 'Sudah Berlangsung / Selesai (Waktu kegiatan sudah lewat)';
        }
    }

    // ── AJAX Live Search, Filter, and Pagination ───────────────────
    let searchTimer;
    let currentFetchController = null;

    // Helper to get checked radio value
    function getSelectedStatus() {
        const checkedRadio = document.querySelector('input[name="status"]:checked');
        return checkedRadio ? checkedRadio.value : '';
    }

    function fetchActivities(page = null) {
        const searchInput  = document.getElementById('search');
        const container    = document.getElementById('activities-container');
        if (!container) return;

        if (currentFetchController) {
            currentFetchController.abort();
        }
        const controller = new AbortController();
        currentFetchController = controller;

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

        // ── Filter Lanjutan params ────────────────────────────────────
        const dateFrom  = document.getElementById('date_from')?.value;
        const dateTo    = document.getElementById('date_to')?.value;
        const location  = document.getElementById('location')?.value;
        const sort      = document.getElementById('sort')?.value;
        const hasDocs   = document.getElementById('has_documents')?.checked;

        if (dateFrom && dateTo) {
            params.append('date_from', dateFrom);
            params.append('date_to', dateTo);
        }
        if (location) {
            params.append('location', location);
        }
        if (sort) {
            params.append('sort', sort);
        }
        if (hasDocs) {
            params.append('has_documents', '1');
        }

        updateAdvancedBadge();

        const url = '{{ route("admin.activities.index") }}?' + params.toString();

        // Update browser URL without reload
        window.history.pushState({}, '', url);

        // Show a subtle skeleton loading state on the container
        container.style.opacity = '0.45';
        container.style.pointerEvents = 'none';
        container.style.transition = 'opacity 0.15s ease';

        const timeoutId = setTimeout(function() { controller.abort('timeout'); }, 10000);

        // Fetch partial view HTML
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: controller.signal
        })
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) throw new Error('Server HTTP ' + response.status);
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '';
            container.style.pointerEvents = '';
            if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
            currentFetchController = null;
        })
        .catch(err => {
            if (typeof hideGlobalLoading === 'function') hideGlobalLoading();
        });
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

        // Check if any advanced filter field has a value
        function hasAnyAdvancedFilterValue() {
            const dateFrom  = (document.getElementById('date_from')?.value || '').trim();
            const dateTo    = (document.getElementById('date_to')?.value || '').trim();
            const loc       = (document.getElementById('location')?.value || '').trim();
            const sort      = document.getElementById('sort')?.value;
            const hasDocs   = document.getElementById('has_documents')?.checked;

            const hasBothDates = !!(dateFrom && dateTo);

            if (hasBothDates) return true;
            if (loc !== '') return true;
            if (sort && sort !== 'newest') return true;
            if (hasDocs === true) return true;

            return false;
        }

        // Validate "Terapkan Filter" button state
        let currentActivityDateMode = 'single';

        function checkModalFilterValidation() {
            const btnApply = document.getElementById('btn-apply-modal');
            const msgEl    = document.getElementById('date-validation-msg');
            if (!btnApply) return;

            const dateFrom = (document.getElementById('date_from')?.value || '').trim();
            const dateTo   = (document.getElementById('date_to')?.value || '').trim();

            const isPartialDate     = (dateFrom && !dateTo) || (!dateFrom && dateTo);
            const isRangeSameDate   = (currentActivityDateMode === 'range' && dateFrom && dateTo && dateFrom === dateTo);
            const isRangeBeforeDate = (currentActivityDateMode === 'range' && dateFrom && dateTo && dateTo < dateFrom);

            if (msgEl) {
                if (isPartialDate) {
                    msgEl.textContent = '* Isi tanggal dari dan tanggal sampai pada mode rentang.';
                    msgEl.classList.remove('hidden');
                } else if (isRangeBeforeDate) {
                    msgEl.textContent = '* Tanggal sampai tidak boleh sebelum tanggal dari.';
                    msgEl.classList.remove('hidden');
                } else if (isRangeSameDate) {
                    msgEl.textContent = '* Pada mode rentang, tanggal sampai tidak boleh sama dengan tanggal dari. Gunakan mode 1 Hari untuk tanggal yang sama.';
                    msgEl.classList.remove('hidden');
                } else {
                    msgEl.classList.add('hidden');
                }
            }

            if (isPartialDate || isRangeSameDate || isRangeBeforeDate) {
                btnApply.disabled = true;
                btnApply.style.opacity = '0.5';
                btnApply.style.cursor = 'not-allowed';
                btnApply.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            const isValid = hasAnyAdvancedFilterValue();
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
            fetchActivities();
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

        // ── Segmented Date Mode Switcher (Satu Hari vs Rentang Tanggal) ──
        window.setActivityDateMode = function(mode) {
            currentActivityDateMode = mode;
            const btnSingle  = document.getElementById('btn-date-mode-single');
            const btnRange   = document.getElementById('btn-date-mode-range');
            const boxSingle  = document.getElementById('container-date-single');
            const boxRange   = document.getElementById('container-date-range');
            const dateFrom   = document.getElementById('date_from');
            const dateTo     = document.getElementById('date_to');
            const dateSingle = document.getElementById('date_single');

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

        window.syncSingleDateToRange = function(val) {
            const dateFrom = document.getElementById('date_from');
            const dateTo   = document.getElementById('date_to');
            if (dateFrom) dateFrom.value = val;
            if (dateTo)   dateTo.value   = val;
            checkModalFilterValidation();
        };

        window.refreshActivityDateModes = function() {
            const dateFrom = document.getElementById('date_from')?.value || '';
            const dateTo   = document.getElementById('date_to')?.value || '';
            if (dateFrom && dateTo && dateFrom !== dateTo) {
                setActivityDateMode('range');
            } else {
                setActivityDateMode('single');
                if (dateFrom) {
                    const dateSingle = document.getElementById('date_single');
                    if (dateSingle) dateSingle.value = dateFrom;
                }
            }
        };

        // Inisialisasi Mode Filter Tanggal berdasarkan URL request
        refreshActivityDateModes();

        // Helper to clear advanced filter inputs
        function clearAdvancedFilters() {
            const dateFrom   = document.getElementById('date_from');
            const dateTo     = document.getElementById('date_to');
            const dateSingle = document.getElementById('date_single');
            const loc        = document.getElementById('location');
            const sort       = document.getElementById('sort');
            const hasDocs    = document.getElementById('has_documents');
            if (dateFrom)   dateFrom.value = '';
            if (dateTo)     dateTo.value = '';
            if (dateSingle) dateSingle.value = '';
            if (loc)        loc.value = '';
            if (sort)       sort.value = 'newest';
            if (hasDocs)    hasDocs.checked = false;
            setActivityDateMode('single');
            checkModalFilterValidation();
        }


        // Listen for change on status radio buttons
        const statusRadios = document.querySelectorAll('input[name="status"]');
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function () {
                clearAdvancedFilters();
                const btnAdv = document.getElementById('btn-advanced');
                if (btnAdv) btnAdv.classList.remove('active');
                fetchActivities();
            });
        });

        // Update button active state
        function updateAdvancedBadge() {
            const btnAdv = document.getElementById('btn-advanced');
            const isAdvActive = hasAnyAdvancedFilterValue();

            if (btnAdv) {
                if (isAdvActive) {
                    btnAdv.classList.add('active');
                    statusRadios.forEach(r => r.checked = false);
                } else {
                    btnAdv.classList.remove('active');
                }
            }
            checkModalFilterValidation();
        }
        window.updateAdvancedBadge = updateAdvancedBadge;
        updateAdvancedBadge();



        // Handle AJAX Pagination clicks
        const container = document.getElementById('activities-container');
        if (container) {
            container.addEventListener('click', function (e) {
                const target = e.target.closest('a[href*="page="], .pagination a, a[rel="next"], a[rel="prev"], nav[role="navigation"] a');
                if (target) {
                    e.preventDefault();
                    if (typeof showGlobalLoading === 'function') showGlobalLoading('Memuat halaman...');
                    const url = new URL(target.href);
                    const page = url.searchParams.get('page');
                    fetchActivities(page);
                    // Smooth scroll to top of index content
                    container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }
    });

    // ── Helper: Lock / Unlock modal ────────────────────────────────────
    // PENTING: Input/select/textarea TIDAK di-disable karena browser tidak
    // menyertakan disabled fields dalam form submission — nilai akan hilang!
    // Pengamanan dilakukan via overlay penuh + disable tombol saja.
    function lockModalForm(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        // Disable hanya tombol (bukan input/select/textarea)
        modal.querySelectorAll('button').forEach(function (el) {
            el.dataset.wasDisabled = el.disabled ? '1' : '0';
            el.disabled = true;
        });
        // Overlay full-cover agar user tidak bisa klik/mengedit apapun secara visual
        const box = modal.querySelector('.modal-box');
        if (box) {
            box.style.position = 'relative';
            let ol = document.getElementById(modalId + '-lock-overlay');
            if (!ol) {
                ol = document.createElement('div');
                ol.id = modalId + '-lock-overlay';
                ol.style.cssText = 'position:absolute;inset:0;z-index:50;cursor:not-allowed;border-radius:inherit;background:rgba(255,255,255,0.38);pointer-events:all;';
                box.appendChild(ol);
            }
        }
        modal.dataset.locked = '1';
    }

    function unlockModalForm(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        modal.querySelectorAll('button').forEach(function (el) {
            if (el.dataset.wasDisabled !== '1') el.disabled = false;
            delete el.dataset.wasDisabled;
        });
        const ol = document.getElementById(modalId + '-lock-overlay');
        if (ol) ol.remove();
        modal.dataset.locked = '0';
    }

    // ── Form Submit Loading Spinners ───────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        // Create form
        const createForm = document.getElementById('create-form');
        if (createForm) {
            createForm.addEventListener('submit', function () {
                lockModalForm('create-modal');
                const btn = document.getElementById('btn-create-submit');
                setButtonLoading(btn, true);
                const timer = armSubmitTimeout('create-modal', function(id) {
                    unlockModalForm(id);
                    setButtonLoading(btn, false);
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }

        // Edit form
        const editForm = document.getElementById('edit-form');
        if (editForm) {
            editForm.addEventListener('submit', function () {
                lockModalForm('edit-modal');
                const btn = document.getElementById('btn-edit-submit');
                setButtonLoading(btn, true);
                const timer = armSubmitTimeout('edit-modal', function(id) {
                    unlockModalForm(id);
                    setButtonLoading(btn, false);
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }

        // Delete form
        const deleteForm = document.getElementById('delete-form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function () {
                lockModalForm('delete-modal');
                const btn = document.getElementById('btn-delete-submit');
                setButtonLoading(btn, true);
                const timer = armSubmitTimeout('delete-modal', function(id) {
                    unlockModalForm(id);
                    setButtonLoading(btn, false);
                }, 20000);
                window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
            });
        }
    });


    // ── Auto-open Modals on Validation Error or ?create/?edit query ──
    document.addEventListener('DOMContentLoaded', function () {

        // Create modal required fields listeners
        ['c-title', 'c-date', 'c-time', 'c-location'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const handler = function() {
                    syncStatusFromDate('c-date', 'c-time', 'c-status', 'c-status-display');
                    validateCreateModalForm();
                };
                el.addEventListener('input', handler);
                el.addEventListener('change', handler);
            }
        });
        validateCreateModalForm();

        // Edit modal inputs listeners
        ['e-title', 'e-date', 'e-time', 'e-location', 'e-description', 'e-status'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const handler = function() {
                    syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
                    validateEditModalForm();
                };
                el.addEventListener('input', handler);
                el.addEventListener('change', handler);
            }
        });

        @if($errors->any() && old('_modal') === 'create')
            openModal('create-modal');
            // Sync date lock on re-open
            syncStatusFromDate('c-date', 'c-time', 'c-status', 'c-status-display');
        @endif

        @if($errors->any() && old('_modal') === 'edit')
            // Edit modal - form already has old() values from PHP, just open it
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
            storeEditModalInitialState();
        @endif

        @if($editActivity)
            // Triggered from show page ?edit=id link
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-time', 'e-status', 'e-status-display');
            storeEditModalInitialState();
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
