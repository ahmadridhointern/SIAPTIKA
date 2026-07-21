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

            {{-- Delete Modal (embedded in show page) --}}
            <div id="show-delete-modal" class="modal-backdrop hidden" onclick="if(event.target===event.currentTarget)this.classList.add('hidden')">
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
                                <button type="button" onclick="document.getElementById('show-delete-modal').classList.add('hidden');document.body.style.overflow='';"
                                        class="flex-1 text-center text-xs font-mono font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-150 cursor-pointer"
                                        style="background:none;">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
            function openDeleteModalShow(id, title) {
                document.getElementById('show-delete-name').textContent = '"' + title + '"';
                document.getElementById('show-delete-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.getElementById('show-delete-modal').classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
            </script>

        </div>

    </div>

</x-layouts.admin>
