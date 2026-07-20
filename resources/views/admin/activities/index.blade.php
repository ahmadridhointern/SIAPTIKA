<x-layouts.admin title="Daftar Kegiatan">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="font-serif text-4xl text-[#1A1A1A] tracking-tight">
                    Jadwal Kegiatan
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Kelola, pantau, dan publikasikan seluruh kegiatan Bidang APTIKA.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.activities.create') }}" class="btn-primary">
                    + Tambah Kegiatan
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="mb-6 alert-error" style="background-color: #f0fdf4; border-color: #bbf7d0; border-left-color: #22c55e; color: #15803d;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 alert-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters & Search --}}
    <div class="card-serif p-6 mb-8 bg-[#FFFFFF]">
        <form method="GET" action="{{ route('admin.activities.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            
            {{-- Search Input --}}
            <div class="flex-1 w-full">
                <label for="search" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                    Cari Kegiatan
                </label>
                <input 
                    id="search" 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Masukkan judul atau tempat..." 
                    class="input-serif"
                >
            </div>

            {{-- Status Filter --}}
            <div class="w-full md:w-48">
                <label for="status" class="block mb-1.5 text-xs font-mono uppercase tracking-wider text-[#6B6B6B]">
                    Status
                </label>
                <select id="status" name="status" class="input-serif" style="padding-right: 2rem;">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Direncana (Scheduled)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 w-full md:w-auto">
                <button type="submit" class="btn-primary flex-1 md:flex-none">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.activities.index') }}" class="block text-center text-xs font-mono tracking-wider font-semibold py-3 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200 w-full md:w-auto">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Activities Table --}}
    <div class="card-serif bg-[#FFFFFF] overflow-hidden mb-6">
        @if($activities->isEmpty())
            <div class="p-12 text-center text-[#6B6B6B]">
                <p class="font-serif text-lg text-[#1A1A1A] mb-2">Tidak Ada Kegiatan ditemukan</p>
                <p class="text-sm">Silakan ubah kata kunci pencarian Anda atau tambahkan kegiatan baru.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-[#E8E4DF] bg-[#FAFAF8]">
                            <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Detail Kegiatan</th>
                            <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Jadwal</th>
                            <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Tempat</th>
                            <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium">Status</th>
                            <th class="px-6 py-4 font-mono text-[0.7rem] uppercase tracking-wider text-[#6B6B6B] font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            @php
                                $isPast = $activity->activity_date->lt(today());
                            @endphp
                            <tr class="border-b border-[#E8E4DF] hover:bg-[#F5F3F0]/50 transition-colors duration-150">
                                
                                {{-- Judul & Deskripsi --}}
                                <td class="px-6 py-4">
                                    <div class="font-serif text-base text-[#1A1A1A] font-semibold">
                                        {{ $activity->title }}
                                    </div>
                                    @if($activity->description)
                                        <div class="text-xs text-[#6B6B6B] line-clamp-1 mt-0.5">
                                            {{ $activity->description }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Jadwal --}}
                                <td class="px-6 py-4 text-sm text-[#1A1A1A] whitespace-nowrap">
                                    <div>{{ $activity->activity_date->isoFormat('D MMM YYYY') }}</div>
                                    <div class="text-xs text-[#6B6B6B] font-mono mt-0.5">
                                        {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                                    </div>
                                </td>

                                {{-- Tempat --}}
                                <td class="px-6 py-4 text-sm text-[#6B6B6B]">
                                    {{ $activity->location }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 text-sm whitespace-nowrap">
                                    @if($activity->status === 'completed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200">
                                            Selesai
                                        </span>
                                    @else
                                        @if($activity->activity_date->isToday())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200">
                                                Hari Ini
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.7rem] font-mono font-medium uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200">
                                                Direncana
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-sm text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-3">
                                        
                                        {{-- Detail (selalu aktif) --}}
                                        <a href="{{ route('admin.activities.show', $activity->id) }}" 
                                           class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                                            Detail
                                        </a>

                                        @if(!$isPast)
                                            <span class="text-[#E8E4DF] text-xs">|</span>
                                            
                                            {{-- Ubah (hanya kegiatan yang belum lewat) --}}
                                            <a href="{{ route('admin.activities.edit', $activity->id) }}" 
                                               class="text-xs font-mono font-semibold text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                                                Ubah
                                            </a>

                                            {{-- Hapus (hanya kegiatan yang belum lewat) --}}
                                            @if($activity->documents()->count() === 0)
                                                <span class="text-[#E8E4DF] text-xs">|</span>
                                                <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-mono font-semibold text-red-600 hover:text-red-800 transition-colors duration-150 bg-none border-none p-0 cursor-pointer">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Pagination --}}
    @if($activities->hasPages())
        <div class="flex justify-center mt-6">
            {{ $activities->links() }}
        </div>
    @endif

</x-layouts.admin>
