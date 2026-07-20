<x-layouts.admin title="Ubah Kegiatan">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-4xl text-[#1A1A1A] tracking-tight">
                    Ubah Kegiatan
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Perbarui informasi rincian kegiatan Bidang APTIKA.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.activities.index') }}" class="block text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="card-serif card-serif-accent p-8 md:p-10 bg-[#FFFFFF] max-w-3xl">
        <form method="POST" action="{{ route('admin.activities.update', $activity->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="space-y-6">

                {{-- Judul Kegiatan --}}
                <div>
                    <label for="title" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Judul Kegiatan
                    </label>
                    <input 
                        id="title" 
                        type="text" 
                        name="title" 
                        value="{{ old('title', $activity->title) }}" 
                        placeholder="Contoh: Rapat Evaluasi Smart City Semester I" 
                        class="input-serif @error('title') border-red-400 @enderror"
                        required
                    >
                    @error('title')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grid Tanggal & Waktu --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Tanggal --}}
                    <div>
                        <label for="activity_date" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                            Tanggal Pelaksanaan
                        </label>
                        <input 
                            id="activity_date" 
                            type="date" 
                            name="activity_date" 
                            value="{{ old('activity_date', $activity->activity_date->toDateString()) }}" 
                            class="input-serif @error('activity_date') border-red-400 @enderror"
                            required
                        >
                        @error('activity_date')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <label for="time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                            Waktu Pelaksanaan
                        </label>
                        <input 
                            id="time" 
                            type="time" 
                            name="time" 
                            value="{{ old('time', \Carbon\Carbon::parse($activity->time)->format('H:i')) }}" 
                            class="input-serif @error('time') border-red-400 @enderror"
                            required
                        >
                        @error('time')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Tempat --}}
                <div>
                    <label for="location" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Tempat Pelaksanaan
                    </label>
                    <input 
                        id="location" 
                        type="text" 
                        name="location" 
                        value="{{ old('location', $activity->location) }}" 
                        placeholder="Contoh: Ruang Rapat Bidang APTIKA Lt. 3" 
                        class="input-serif @error('location') border-red-400 @enderror"
                        required
                    >
                    @error('location')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Status Kegiatan
                    </label>
                    <select id="status" name="status" class="input-serif @error('status') border-red-400 @enderror">
                        <option value="scheduled" {{ old('status', $activity->status) === 'scheduled' ? 'selected' : '' }}>Direncana (Scheduled)</option>
                        <option value="completed" {{ old('status', $activity->status) === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Deskripsi Kegiatan
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5" 
                        placeholder="Tuliskan agenda rapat, catatan pendukung, atau detail kegiatan di sini..." 
                        class="input-serif @error('description') border-red-400 @enderror"
                        style="height: auto; padding-top: 0.75rem; padding-bottom: 0.75rem;"
                    >{{ old('description', $activity->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="rule-line my-6">

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-primary">
                        Perbarui Kegiatan
                    </button>
                    <a href="{{ route('admin.activities.index') }}" class="text-sm font-mono text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                        Batalkan
                    </a>
                </div>

            </div>

        </form>
    </div>

</x-layouts.admin>
