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
                {{-- Back button — icon only, text on hover --}}
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

    {{-- Detail Layout Grid (Asymmetric) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: Informasi Utama --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- Detail Card --}}
            <div class="card-serif p-8 bg-[#FFFFFF] space-y-6">
                
                {{-- Metadata Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-[#F5F3F0]">
                    
                    {{-- Waktu & Tanggal --}}
                    <div>
                        <span class="small-caps text-[0.65rem] block mb-1">Waktu Pelaksanaan</span>
                        <div class="text-[#1A1A1A] font-medium text-base">
                            {{ $activity->activity_date->isoFormat('dddd, D MMMM YYYY') }}
                        </div>
                        <div class="text-[#6B6B6B] text-xs font-mono mt-0.5">
                            Pukul {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                        </div>
                    </div>

                    {{-- Lokasi / Tempat --}}
                    <div>
                        <span class="small-caps text-[0.65rem] block mb-1">Tempat / Ruangan</span>
                        <div class="text-[#1A1A1A] font-medium text-base">
                            {{ $activity->location }}
                        </div>
                        <div class="text-[#6B6B6B] text-xs mt-0.5">
                            Bidang APTIKA Diskominfotik Riau
                        </div>
                    </div>

                </div>

                {{-- Deskripsi --}}
                <div>
                    <span class="small-caps text-[0.65rem] block mb-2">Deskripsi & Agenda</span>
                    <div class="text-sm text-[#1A1A1A] leading-relaxed whitespace-pre-line" style="font-family: 'Source Sans 3', system-ui, sans-serif;">
                        {!! $activity->description ? e($activity->description) : '<em class="text-[#6B6B6B]">Tidak ada deskripsi kegiatan.</em>' !!}
                    </div>
                </div>

            </div>

            {{-- Dokumen Terkait --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-serif text-xl text-[#1A1A1A]">
                        Dokumen & Lampiran
                    </h2>
                    <span class="small-caps text-[0.65rem]">{{ $activity->documents->count() }} Lampiran</span>
                </div>

                <div class="card-serif bg-[#FFFFFF] divide-y divide-[#E8E4DF] overflow-hidden">
                    @if($activity->documents->isEmpty())
                        <div class="p-8 text-center text-[#6B6B6B] text-sm">
                            Belum ada dokumen yang diunggah untuk kegiatan ini.
                        </div>
                    @else
                        @foreach($activity->documents as $doc)
                            <div class="p-5 flex items-center justify-between hover:bg-[#F5F3F0]/30 transition-colors duration-150">
                                <div>
                                    <div class="text-sm font-semibold text-[#1A1A1A]">
                                        {{ $doc->file_name }}
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.6rem] font-mono font-medium uppercase tracking-wider bg-gold-50 text-[#B8860B] border border-amber-200" style="background-color: #FAFAF8;">
                                            {{ $doc->document_type }}
                                        </span>
                                        <span class="text-[0.65rem] text-[#6B6B6B] font-mono">
                                            Diunggah {{ $doc->created_at->isoFormat('D MMM YYYY') }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ $doc->file_url }}" target="_blank" class="text-xs font-mono font-semibold text-[#B8860B] hover:text-[#D4A84B] transition-colors duration-150">
                                        Buka Berkas ↗
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        {{-- Right: Status & Action Panel --}}
        <div class="space-y-6">
            
            {{-- Panel Status --}}
            <div class="card-serif p-6 bg-[#FFFFFF] space-y-4">
                <div>
                    <span class="small-caps text-[0.65rem] block mb-1">Status Kegiatan</span>
                    @if($activity->status === 'completed')
                        <div class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200">
                            Selesai (Completed)
                        </div>
                    @else
                        @if($activity->activity_date->isToday())
                            <div class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                Hari Ini
                            </div>
                        @else
                            <div class="inline-flex items-center px-2.5 py-1 rounded text-xs font-mono font-medium uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                                Direncana (Scheduled)
                            </div>
                        @endif
                    @endif
                </div>

                <div class="pt-4 border-t border-[#F5F3F0] space-y-2 text-xs text-[#6B6B6B] font-mono">
                    <div>Pembuat: <span class="text-[#1A1A1A]">{{ $activity->user->name }}</span></div>
                    <div>Email: <span class="text-[#1A1A1A]">{{ $activity->user->email }}</span></div>
                    <div>Dibuat: <span class="text-[#1A1A1A]">{{ $activity->created_at->isoFormat('D MMMM YYYY H:i') }} WIB</span></div>
                </div>
            </div>

            {{-- ─── Panel: Unggah Dokumen ───────────────────────────── --}}
            <div class="card-serif p-6 bg-[#FFFFFF] space-y-4">
                <span class="small-caps text-[0.65rem] block">Unggah Dokumen</span>

                {{-- Validation errors for upload --}}
                @if($errors->any())
                    <div class="rounded p-3 bg-red-50 border border-red-200 space-y-1">
                        @foreach($errors->all() as $error)
                            <p class="text-xs text-red-600 font-mono">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="form-upload-doc"
                      method="POST"
                      action="{{ route('admin.activities.documents.store', $activity) }}"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf

                    {{-- Jenis Dokumen --}}
                    <div>
                        <label for="document_type"
                               class="small-caps text-[0.6rem] block mb-1.5">
                            Jenis Dokumen <span class="text-red-400">*</span>
                        </label>
                        <select id="document_type"
                                name="document_type"
                                class="input-serif text-sm h-10 pr-8 appearance-none cursor-pointer
                                       {{ $errors->has('document_type') ? 'border-red-400' : '' }}"
                                style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B6B6B' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");
                                         background-repeat: no-repeat;
                                         background-position: right 0.625rem center;
                                         background-size: 0.875rem;">
                            <option value="" disabled {{ old('document_type') ? '' : 'selected' }}>
                                — Pilih jenis —
                            </option>
                            <option value="surat"        {{ old('document_type') === 'surat'        ? 'selected' : '' }}>Surat / Dokumen</option>
                            <option value="notulen"      {{ old('document_type') === 'notulen'      ? 'selected' : '' }}>Notulen</option>
                            <option value="dokumentasi"  {{ old('document_type') === 'dokumentasi'  ? 'selected' : '' }}>Dokumentasi</option>
                        </select>
                    </div>

                    {{-- File Input --}}
                    <div>
                        <label for="upload_file"
                               class="small-caps text-[0.6rem] block mb-1.5">
                            Pilih Berkas <span class="text-red-400">*</span>
                        </label>

                        {{-- Custom file input area --}}
                        <label for="upload_file"
                               id="upload-file-label"
                               class="flex flex-col items-center justify-center gap-2 w-full
                                      border border-dashed rounded p-4 cursor-pointer
                                      transition-colors duration-150
                                      {{ $errors->has('file') ? 'border-red-400 bg-red-50' : 'border-[#E8E4DF] hover:border-[#B8860B] hover:bg-[#FAFAF8]' }}">
                            <svg class="w-6 h-6 text-[#B8860B]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <span id="upload-file-name"
                                  class="text-xs font-mono text-[#6B6B6B] text-center leading-snug">
                                Klik untuk pilih berkas<br>
                                <span class="text-[0.6rem] opacity-70">PDF, Word, JPG, PNG, MP4 — Maks. 10 MB</span>
                            </span>
                        </label>
                        <input id="upload_file"
                               name="file"
                               type="file"
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4"
                               class="sr-only"
                               onchange="updateFileName(this)">
                    </div>

                    {{-- Submit Button --}}
                    <button id="btn-upload-doc"
                            type="submit"
                            class="btn-primary w-full justify-center">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        Unggah Dokumen
                    </button>
                </form>
            </div>

            {{-- Danger Zone (Hapus via Modal) --}}
            @if(!$activity->activity_date->lt(today()) && $activity->documents->count() === 0)
                <div class="card-serif p-6 bg-[#FFFFFF] border-red-200">
                    <span class="small-caps text-[0.65rem] text-red-600 block mb-2">Zona Bahaya</span>
                    <p class="text-xs text-[#6B6B6B] leading-relaxed mb-4">
                        Tindakan penghapusan kegiatan bersifat permanen dan tidak dapat dibatalkan.
                    </p>
                    <button type="button"
                            onclick="openDeleteModalShow({{ $activity->id }}, '{{ addslashes($activity->title) }}')"
                            class="w-full text-center text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-red-200 text-red-600 hover:bg-red-50 transition-all duration-200 cursor-pointer"
                            style="background:none;">
                        Hapus Kegiatan
                    </button>
                </div>
            @endif

            @push('modals')
            {{-- Delete Modal (embedded in show page) --}}
            <div id="show-delete-modal" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget) closeDeleteModalShow()">
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
                                 <button type="submit" class="flex-1 text-center text-xs font-mono font-semibold py-2.5 px-4 rounded border border-red-300 text-red-600 bg-red-50 hover:bg-red-100 transition-all duration-150 cursor-pointer">
                                     Ya, Hapus
                                 </button>
                                 <button type="button" onclick="closeDeleteModalShow()"
                                         class="btn-secondary flex-1">
                                     Batal
                                 </button>
                             </div>
                         </form>
                    </div>
                </div>
            </div>
            @endpush

            <script>
            function openDeleteModalShow(id, title) {
                document.getElementById('show-delete-name').textContent = '"' + title + '"';
                document.getElementById('show-delete-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                const appContent = document.getElementById('app-content');
                if (appContent) appContent.classList.add('modal-open-filter');
            }
            function closeDeleteModalShow() {
                document.getElementById('show-delete-modal').classList.add('hidden');
                document.body.style.overflow = '';
                const appContent = document.getElementById('app-content');
                if (appContent) appContent.classList.remove('modal-open-filter');
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDeleteModalShow();
                }
            });
            </script>

        </div>

    </div>
    {{-- ─── Upload: Filename Display & Loading Spinner ─────── --}}
    <script>
        /**
         * Tampilkan nama file yang dipilih di area drag-drop.
         * Dipanggil via onchange pada <input type="file">.
         */
        function updateFileName(input) {
            var label = document.getElementById('upload-file-name');
            if (!label) return;

            if (input.files && input.files.length > 0) {
                var file = input.files[0];
                var sizeMB = (file.size / 1024 / 1024).toFixed(2);
                label.innerHTML =
                    '<span class="font-semibold text-[#1A1A1A]">' +
                        escHtml(file.name) +
                    '</span><br>' +
                    '<span class="text-[0.6rem] opacity-70">' + sizeMB + ' MB</span>';
            } else {
                label.innerHTML =
                    'Klik untuk pilih berkas<br>' +
                    '<span class="text-[0.6rem] opacity-70">PDF, Word, JPG, PNG, MP4 — Maks. 10 MB</span>';
            }
        }

        /** Escape HTML untuk nama file yang ditampilkan. */
        function escHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        /**
         * Aktifkan loading spinner pada tombol upload saat form disubmit.
         * Menggunakan setButtonLoading() yang sudah ada di admin.blade.php.
         */
        (function () {
            var form = document.getElementById('form-upload-doc');
            var btn  = document.getElementById('btn-upload-doc');
            if (!form || !btn) return;

            form.addEventListener('submit', function () {
                if (typeof setButtonLoading === 'function') {
                    setButtonLoading(btn, true);
                }
            });
        })();
    </script>

</x-layouts.admin>
