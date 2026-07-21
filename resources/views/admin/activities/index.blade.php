<x-layouts.admin title="Daftar Kegiatan">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="font-serif text-4xl text-[#1A1A1A] tracking-tight">Jadwal Kegiatan</h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">Kelola, pantau, dan publikasikan seluruh kegiatan Bidang APTIKA.</p>
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
        <form id="filter-form" method="GET" action="{{ route('admin.activities.index') }}"
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
                           class="input-serif" style="padding-left: 2.5rem;" autocomplete="off">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-full md:w-52">
                <label for="status" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">Status</label>
                <select id="status" name="status" class="input-serif">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Direncana</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2 w-full md:w-auto flex-shrink-0">
                <button type="submit" class="btn-primary flex-1 md:flex-none md:px-5" style="min-height: 3rem; font-size: 0.8rem;">
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
            <form method="POST" action="{{ route('admin.activities.store') }}" novalidate class="px-8 py-6">
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
                                   onchange="syncStatusFromDate('c-date','c-status','c-status-note')"
                                   class="input-serif @if($errors->has('activity_date') && old('_modal') === 'create') border-red-400 bg-red-50 @endif">
                            @if($errors->has('activity_date') && old('_modal') === 'create')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('activity_date') }}</p>
                            @endif
                        </div>
                        <div>
                            <label for="c-time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Waktu <span class="text-red-500">*</span></label>
                            <input id="c-time" type="time" name="time"
                                   value="{{ old('_modal') === 'create' ? old('time') : '' }}"
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
                        <label for="c-status" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Status <span class="text-red-500">*</span></label>
                        <select id="c-status" name="status"
                                class="input-serif @if($errors->has('status') && old('_modal') === 'create') border-red-400 bg-red-50 @endif">
                            <option value="scheduled" {{ (old('_modal') === 'create' && old('status') === 'scheduled') || old('_modal') !== 'create' ? 'selected' : '' }}>Direncana (Scheduled)</option>
                            <option value="completed" {{ old('_modal') === 'create' && old('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        </select>
                        <p id="c-status-note" class="mt-1 text-xs text-[#6B6B6B] hidden"></p>
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

                <div class="flex items-center gap-4 mt-6 pt-5 border-t border-[#E8E4DF]">
                    <button type="submit" class="btn-primary">Simpan Kegiatan</button>
                    <button type="button" onclick="closeModal('create-modal')"
                            class="text-sm font-mono text-[#6B6B6B] hover:text-[#B8860B] transition-colors"
                            style="background:none;border:none;cursor:pointer;">Batalkan</button>
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

            <form id="edit-form" method="POST" action="{{ $editFormAction }}" novalidate class="px-8 py-6">
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
                                   onchange="syncStatusFromDate('e-date','e-status','e-status-note')"
                                   class="input-serif @if($errors->has('activity_date') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif">
                            @if($errors->has('activity_date') && old('_modal') === 'edit')
                                <p class="mt-1 text-xs text-red-600">{{ $errors->first('activity_date') }}</p>
                            @endif
                        </div>
                        <div>
                            <label for="e-time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Waktu <span class="text-red-500">*</span></label>
                            <input id="e-time" type="time" name="time"
                                   value="{{ old('_modal') === 'edit' ? old('time') : ($editActivity ? \Carbon\Carbon::parse($editActivity->time)->format('H:i') : '') }}"
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
                        <label for="e-status" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">Status <span class="text-red-500">*</span></label>
                        <select id="e-status" name="status"
                                class="input-serif @if($errors->has('status') && old('_modal') === 'edit') border-red-400 bg-red-50 @endif">
                            <option value="scheduled" {{ (old('_modal') === 'edit' ? old('status') : $editActivity?->status) === 'scheduled' ? 'selected' : '' }}>Direncana (Scheduled)</option>
                            <option value="completed" {{ (old('_modal') === 'edit' ? old('status') : $editActivity?->status) === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        </select>
                        <p id="e-status-note" class="mt-1 text-xs text-amber-600 hidden"></p>
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

                <div class="flex items-center gap-4 mt-6 pt-5 border-t border-[#E8E4DF]">
                    <button type="submit" class="btn-primary">Perbarui Kegiatan</button>
                    <button type="button" onclick="closeModal('edit-modal')"
                            class="text-sm font-mono text-[#6B6B6B] hover:text-[#B8860B] transition-colors"
                            style="background:none;border:none;cursor:pointer;">Batalkan</button>
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
                                class="flex-1 text-center text-xs font-mono font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-150 cursor-pointer"
                                style="background:none;">
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
        syncStatusFromDate('e-date', 'e-status', 'e-status-note');
        openModal('edit-modal');
    }

    // ── Open Delete Modal ─────────────────────────────────────────────
    function openDeleteModal(id, title) {
        const baseUrl = document.getElementById('activities-url-base').value;
        document.getElementById('delete-form').action = baseUrl + '/' + id;
        document.getElementById('delete-activity-name').textContent = '"' + title + '"';
        openModal('delete-modal');
    }

    // ── Auto-set Status when Past Date (#8) ──────────────────────────
    function syncStatusFromDate(dateId, statusId, noteId) {
        const dateEl   = document.getElementById(dateId);
        const statusEl = document.getElementById(statusId);
        const noteEl   = document.getElementById(noteId);
        if (!dateEl || !statusEl) return;
        const selected = dateEl.value ? new Date(dateEl.value + 'T00:00:00') : null;
        const today    = new Date(); today.setHours(0, 0, 0, 0);
        if (selected && selected < today) {
            statusEl.value = 'completed';
            statusEl.classList.add('status-locked');
            if (noteEl) {
                noteEl.textContent = '⚠ Status dikunci otomatis karena tanggal sudah lewat.';
                noteEl.classList.remove('hidden');
            }
        } else {
            statusEl.classList.remove('status-locked');
            if (noteEl) noteEl.classList.add('hidden');
        }
    }

    // ── Live Search with Debounce (#6) ────────────────────────────────
    (function () {
        const searchInput  = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        if (!searchInput) return;
        let searchTimer;
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                document.getElementById('filter-form').submit();
            }, 600);
        });
        // Also trigger on status change immediately
        if (statusSelect) {
            statusSelect.addEventListener('change', function () {
                document.getElementById('filter-form').submit();
            });
        }
    })();

    // ── Auto-open Modals on Validation Error or ?create/?edit query ──
    document.addEventListener('DOMContentLoaded', function () {

        @if($errors->any() && old('_modal') === 'create')
            openModal('create-modal');
            // Sync date lock on re-open
            syncStatusFromDate('c-date', 'c-status', 'c-status-note');
        @endif

        @if($errors->any() && old('_modal') === 'edit')
            // Edit modal - form already has old() values from PHP, just open it
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-status', 'e-status-note');
        @endif

        @if($editActivity)
            // Triggered from show page ?edit=id link
            openModal('edit-modal');
            syncStatusFromDate('e-date', 'e-status', 'e-status-note');
        @endif

        @if(request()->has('create'))
            openModal('create-modal');
        @endif

        // Apply initial date lock check for any open modals
        syncStatusFromDate('c-date', 'c-status', 'c-status-note');
        syncStatusFromDate('e-date', 'e-status', 'e-status-note');
    });
    </script>

</x-layouts.admin>
