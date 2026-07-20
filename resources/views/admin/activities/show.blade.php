<x-layouts.admin title="Detail Kegiatan">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <span class="small-caps text-[0.65rem] tracking-[0.12em] block mb-1">Rincian Kegiatan</span>
                <h1 class="font-serif text-3xl md:text-4xl text-[#1A1A1A] tracking-tight leading-tight">
                    {{ $activity->title }}
                </h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.activities.index') }}" class="block text-center text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200">
                    Kembali
                </a>
                @if(!$activity->activity_date->lt(today()))
                    <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn-primary py-2.5">
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

            {{-- Danger Zone (Hapus) --}}
            @if(!$activity->activity_date->lt(today()) && $activity->documents->count() === 0)
                <div class="card-serif p-6 bg-[#FFFFFF] border-red-200">
                    <span class="small-caps text-[0.65rem] text-red-600 block mb-2">Zona Bahaya</span>
                    <p class="text-xs text-[#6B6B6B] leading-relaxed mb-4">
                        Tindakan penghapusan kegiatan bersifat permanen dan tidak dapat dibatalkan.
                    </p>
                    <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-center text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-red-200 text-red-600 hover:bg-red-50 transition-all duration-200 cursor-pointer">
                            Hapus Kegiatan
                        </button>
                    </form>
                </div>
            @endif

        </div>

    </div>

</x-layouts.admin>
