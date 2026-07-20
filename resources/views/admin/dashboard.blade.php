<x-layouts.admin title="Dashboard">

    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <span class="h-px flex-1" style="background-color: #E8E4DF;"></span>
            <span class="small-caps">Ringkasan</span>
            <span class="h-px flex-1" style="background-color: #E8E4DF;"></span>
        </div>
        <h1 class="font-serif text-3xl" style="color: #1A1A1A; letter-spacing: -0.01em;">
            Dashboard
        </h1>
        <p class="mt-1 text-sm" style="color: #6B6B6B;">
            Selamat datang, {{ Auth::guard('admin')->user()->name }}.
        </p>
    </div>

    {{-- Placeholder Info --}}
    <div class="card-serif card-serif-accent p-8 text-center">
        <span class="small-caps block mb-3">Sprint 2 — In Progress</span>
        <p class="font-serif text-xl" style="color: #1A1A1A;">
            Autentikasi Admin berhasil.
        </p>
        <p class="mt-2 text-sm" style="color: #6B6B6B;">
            Modul Kegiatan akan diimplementasi pada langkah berikutnya.
        </p>
    </div>

</x-layouts.admin>
