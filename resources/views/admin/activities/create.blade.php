<x-layouts.admin title="Tambah Kegiatan">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Administrasi APTIKA</span>
            <span class="h-px flex-1 bg-[#E8E4DF]"></span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-4xl text-[#1A1A1A] tracking-tight">
                    Tambah Kegiatan Baru
                </h1>
                <p class="mt-2 text-sm text-[#6B6B6B]">
                    Tambahkan rincian rencana kegiatan Bidang APTIKA. Kolom bertanda <span class="text-red-500 font-semibold">*</span> wajib diisi.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.activities.index') }}" 
                   class="block text-xs font-mono tracking-wider font-semibold py-2.5 px-4 rounded border border-[#E8E4DF] text-[#6B6B6B] hover:text-[#B8860B] hover:border-[#B8860B] transition-all duration-200">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Ringkasan Error (validation-errors component) --}}
    <x-validation-errors class="max-w-3xl" />

    {{-- Form Card --}}
    <div class="card-serif card-serif-accent p-8 md:p-10 bg-[#FFFFFF] max-w-3xl">
        <form method="POST" action="{{ route('admin.activities.store') }}" novalidate>
            @csrf

            <div class="space-y-6">

                {{-- Judul Kegiatan --}}
                <div>
                    <label for="title" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Judul Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        maxlength="255"
                        placeholder="Contoh: Rapat Evaluasi Smart City Semester I"
                        class="input-serif @error('title') border-red-400 bg-red-50 @enderror"
                        autocomplete="off"
                    >
                    <div class="flex items-start justify-between mt-1.5 gap-4">
                        @error('title')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-xs text-[#6B6B6B]">Minimal 5, maksimal 255 karakter.</p>
                        @enderror
                        <span class="text-xs text-[#6B6B6B] font-mono whitespace-nowrap flex-shrink-0" id="title-count">
                            {{ strlen(old('title', '')) }}/255
                        </span>
                    </div>
                </div>

                {{-- Grid Tanggal & Waktu --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Tanggal --}}
                    <div>
                        <label for="activity_date" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                            Tanggal Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="activity_date"
                            type="date"
                            name="activity_date"
                            value="{{ old('activity_date') }}"
                            class="input-serif @error('activity_date') border-red-400 bg-red-50 @enderror"
                        >
                        @error('activity_date')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="mt-1.5 text-xs text-[#6B6B6B]">Format: Tahun-Bulan-Hari.</p>
                        @enderror
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <label for="time" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                            Waktu Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="time"
                            type="time"
                            name="time"
                            value="{{ old('time') }}"
                            class="input-serif @error('time') border-red-400 bg-red-50 @enderror"
                        >
                        @error('time')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="mt-1.5 text-xs text-[#6B6B6B]">Format 24 jam, contoh: 08:00, 14:30.</p>
                        @enderror
                    </div>

                </div>

                {{-- Tempat --}}
                <div>
                    <label for="location" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Tempat Pelaksanaan <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="location"
                        type="text"
                        name="location"
                        value="{{ old('location') }}"
                        maxlength="255"
                        placeholder="Contoh: Ruang Rapat Bidang APTIKA Lt. 3"
                        class="input-serif @error('location') border-red-400 bg-red-50 @enderror"
                        autocomplete="off"
                    >
                    @error('location')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @else
                        <p class="mt-1.5 text-xs text-[#6B6B6B]">Minimal 3, maksimal 255 karakter.</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Status Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" class="input-serif @error('status') border-red-400 bg-red-50 @enderror">
                        <option value="scheduled" {{ old('status', 'scheduled') === 'scheduled' ? 'selected' : '' }}>Direncana (Scheduled)</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    </select>
                    @error('status')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block mb-1.5 text-sm font-medium text-[#1A1A1A]">
                        Deskripsi Kegiatan
                        <span class="text-xs text-[#6B6B6B] font-normal">(opsional)</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        maxlength="2000"
                        placeholder="Tuliskan agenda rapat, catatan pendukung, atau detail kegiatan di sini..."
                        class="input-serif @error('description') border-red-400 bg-red-50 @enderror"
                        style="height: auto; padding-top: 0.75rem; padding-bottom: 0.75rem;"
                    >{{ old('description') }}</textarea>
                    <div class="flex items-start justify-between mt-1.5 gap-4">
                        @error('description')
                            <p class="text-xs text-red-600 flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @else
                            <p class="text-xs text-[#6B6B6B]">Isian bersifat opsional, maksimal 2000 karakter.</p>
                        @enderror
                        <span class="text-xs text-[#6B6B6B] font-mono whitespace-nowrap flex-shrink-0" id="description-count">
                            {{ strlen(old('description', '')) }}/2000
                        </span>
                    </div>
                </div>

                <hr class="rule-line my-2">

                {{-- Submit Buttons --}}
                <div class="flex items-center gap-4">
                    <button type="submit" class="btn-primary">
                        Simpan Kegiatan
                    </button>
                    <a href="{{ route('admin.activities.index') }}" 
                       class="text-sm font-mono text-[#6B6B6B] hover:text-[#B8860B] transition-colors duration-150">
                        Batalkan
                    </a>
                </div>

            </div>

        </form>
    </div>

    {{-- Character Counter Script --}}
    <script>
        function setupCounter(inputId, counterId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            if (!input || !counter) return;
            const update = () => {
                const len = input.value.length;
                counter.textContent = len + '/' + max;
                counter.classList.toggle('text-red-500', len >= max);
                counter.classList.toggle('text-[#6B6B6B]', len < max);
            };
            input.addEventListener('input', update);
        }
        setupCounter('title', 'title-count', 255);
        setupCounter('description', 'description-count', 2000);
    </script>

</x-layouts.admin>
