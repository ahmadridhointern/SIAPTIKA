<x-layouts.employee title="{{ $activity->title }}">

    {{-- Page Header --}}
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-4">
            <span class="small-caps">Rincian Kegiatan</span>
            <span class="h-px flex-1 bg-[#E8E4DF]" aria-hidden="true"></span>
        </div>
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="min-w-0 flex-1">
                <h1 class="font-serif text-3xl md:text-4xl text-[#1A1A1A] tracking-tight leading-tight">
                    {{ $activity->title }}
                </h1>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('employee.activities.index') }}"
                   onclick="__navGo(this, this.href); return false;"
                   class="back-btn"
                   aria-label="Kembali ke daftar kegiatan">
                    <svg class="back-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    <span class="back-btn-text">Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         Grid: 2/3 Kiri (Info Kegiatan) | 1/3 Kanan (Dokumen)
         ════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ══ KIRI (2/3) — Informasi Kegiatan ══ --}}
        <div class="lg:col-span-2 space-y-6">

            <article class="card-serif bg-[#FFFFFF]" aria-label="Informasi kegiatan">

                {{-- Header Kartu: Info Pembuat --}}
                <div class="px-8 py-4 border-b border-[#F5F3F0] flex flex-wrap items-center justify-between gap-x-6 gap-y-1 hover:bg-[#FAFAF8] transition-colors duration-150">
                    <span class="text-xs text-[#6B6B6B] font-mono">
                        Dibuat oleh: <span class="text-[#1A1A1A]">{{ $activity->user->name ?? '—' }}</span>
                    </span>
                    <span class="text-xs text-[#6B6B6B] font-mono">
                        <time datetime="{{ $activity->created_at->format('Y-m-d\TH:i') }}">
                            Dibuat: <span class="text-[#1A1A1A]">{{ $activity->created_at->isoFormat('D MMMM YYYY, HH:mm') }} WIB</span>
                        </time>
                    </span>
                </div>

                <div class="p-8 space-y-6">

                    {{-- Meta: Waktu · Tempat · Status --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pb-7 border-b border-[#F5F3F0]">

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Waktu Pelaksanaan</span>
                            <div class="text-[#1A1A1A] font-medium text-base leading-snug">
                                {{ $activity->activity_date->isoFormat('dddd, D MMMM YYYY') }}
                            </div>
                            <div class="text-[#6B6B6B] text-xs font-mono mt-1">
                                <time datetime="{{ $activity->activity_date->format('Y-m-d') }}T{{ \Carbon\Carbon::parse($activity->time)->format('H:i') }}">
                                    Pukul {{ \Carbon\Carbon::parse($activity->time)->format('H:i') }} WIB
                                </time>
                            </div>
                        </div>

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Tempat / Ruangan</span>
                            <div class="text-[#1A1A1A] font-medium text-base leading-snug">{{ $activity->location }}</div>
                            <div class="text-[#6B6B6B] text-xs mt-1 leading-relaxed">Bidang APTIKA — Diskominfotik Riau</div>
                        </div>

                        <div class="p-3 -m-3 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                            <span class="small-caps text-[0.65rem] block mb-2">Status</span>
                            @if($activity->computed_status === 'Selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200"
                                      role="status" aria-label="Status kegiatan: Selesai">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400" aria-hidden="true"></span>Selesai
                                </span>
                            @elseif($activity->computed_status === 'Sudah Berlangsung')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200"
                                      role="status" aria-label="Status kegiatan: Sudah Berlangsung">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>Sudah Berlangsung
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200"
                                      role="status" aria-label="Status kegiatan: Direncana">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500" aria-hidden="true"></span>Direncana
                                </span>
                            @endif
                        </div>

                    </div>

                    {{-- Deskripsi --}}
                    <div class="p-4 -m-4 rounded-lg hover:bg-[#FAFAF8] transition-colors duration-150">
                        <span class="small-caps text-[0.65rem] block mb-2">Deskripsi &amp; Agenda</span>
                        <div class="text-sm text-[#1A1A1A] leading-relaxed whitespace-pre-line"
                             style="font-family: 'Source Sans 3', system-ui, sans-serif;">
                            {!! $activity->description
                                ? e($activity->description)
                                : '<em class="text-[#6B6B6B]">Tidak ada deskripsi kegiatan.</em>' !!}
                        </div>
                    </div>

                </div>{{-- /p-8 --}}

            </article>

        </div>{{-- /lg:col-span-2 --}}

        {{-- ══ KANAN (1/3) — Dokumen & Lampiran (read-only) ══ --}}
        <div class="space-y-5">

            <section class="card-serif bg-[#FFFFFF] overflow-hidden" aria-label="Dokumen dan lampiran kegiatan">

                {{-- Header: label + total berkas --}}
                <div class="px-5 py-4 border-b border-[#E8E4DF] flex items-center justify-between">
                    <span class="small-caps text-[0.65rem]">Dokumen &amp; Lampiran</span>
                    <div class="flex items-center gap-2">
                        <span class="text-[0.65rem] font-mono text-[#6B6B6B]" aria-label="{{ $documentCounts['all'] }} berkas tersedia">
                            {{ $documentCounts['all'] }} berkas
                        </span>
                        @if($activity->computed_status === 'Selesai')
                            <span class="text-[#E8E4DF] text-xs">|</span>
                            <a href="{{ route('employee.archive.index', ['search' => $activity->title]) }}"
                               class="text-[0.65rem] font-mono text-[#B8860B] hover:text-[#D4A84B] transition-colors"
                               title="Lihat di Arsip"
                               aria-label="Lihat dokumen kegiatan ini di halaman arsip">
                                Arsip →
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Filter tab jenis dokumen --}}
                <div class="px-5 py-3.5 border-b border-[#E8E4DF] bg-[#FAFAF8] flex items-center justify-between gap-1 text-[0.62rem] font-mono flex-nowrap overflow-x-auto"
                     role="navigation" aria-label="Filter jenis dokumen">
                    <a href="{{ route('employee.activities.show', $activity) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ !request('type') ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}"
                       aria-current="{{ !request('type') ? 'page' : 'false' }}"
                       aria-label="Semua ({{ $documentCounts['all'] }})">
                        Semua <span class="opacity-70">({{ $documentCounts['all'] }})</span>
                    </a>
                    <a href="{{ route('employee.activities.show', [$activity, 'type' => 'surat']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'surat' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}"
                       aria-current="{{ request('type') === 'surat' ? 'page' : 'false' }}"
                       aria-label="Surat ({{ $documentCounts['surat'] }})">
                        Surat <span class="opacity-70">({{ $documentCounts['surat'] }})</span>
                    </a>
                    <a href="{{ route('employee.activities.show', [$activity, 'type' => 'notulen']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'notulen' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}"
                       aria-current="{{ request('type') === 'notulen' ? 'page' : 'false' }}"
                       aria-label="Notulen ({{ $documentCounts['notulen'] }})">
                        Notulen <span class="opacity-70">({{ $documentCounts['notulen'] }})</span>
                    </a>
                    <a href="{{ route('employee.activities.show', [$activity, 'type' => 'dokumentasi']) }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="px-1.5 py-0.5 rounded transition-colors duration-150 whitespace-nowrap {{ request('type') === 'dokumentasi' ? 'bg-[#B8860B]/10 text-[#B8860B] font-bold' : 'text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF]' }}"
                       aria-current="{{ request('type') === 'dokumentasi' ? 'page' : 'false' }}"
                       aria-label="Dokumentasi ({{ $documentCounts['dokumentasi'] }})">
                        Dokumentasi <span class="opacity-70">({{ $documentCounts['dokumentasi'] }})</span>
                    </a>
                </div>

                {{-- Daftar Dokumen (scrollable max 3 list jika > 3 dokumen) --}}
                <div class="divide-y divide-[#E8E4DF]" style="{{ $documents->count() > 3 ? 'max-height: 204px; overflow-y: auto;' : '' }}" role="list" aria-label="Daftar dokumen">
                    @if($documentCounts['all'] === 0)
                        {{-- Empty State: belum ada dokumen sama sekali --}}
                        <div class="py-12 flex flex-col items-center gap-3 text-center px-6">
                            <div class="w-10 h-10 rounded-full bg-[#F5F3F0] flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#C9C0B5]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-mono text-[#6B6B6B] leading-relaxed">
                                @if($activity->computed_status === 'Direncana')
                                    Dokumen akan diunggah setelah kegiatan berlangsung.
                                @elseif($activity->computed_status === 'Sudah Berlangsung')
                                    Dokumen sedang diproses untuk diunggah.
                                @else
                                    Belum ada dokumen untuk kegiatan ini.
                                @endif
                            </p>
                        </div>
                    @elseif($documents->isEmpty())
                        {{-- Empty State: filter menghasilkan kosong --}}
                        <div class="py-10 flex flex-col items-center gap-2 text-center px-6">
                            <p class="text-xs font-mono text-[#6B6B6B]">Tidak ada dokumen untuk jenis ini.</p>
                            <a href="{{ route('employee.activities.show', $activity) }}"
                               class="text-[0.65rem] font-mono text-[#B8860B] hover:text-[#D4A84B] transition-colors inline-flex items-center gap-1"
                               aria-label="Tampilkan semua jenis dokumen">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                </svg>
                                Tampilkan semua
                            </a>
                        </div>
                    @else
                        @foreach($documents as $doc)
                            @php
                                $ext = strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION));
                                if (strlen($ext) > 4 || !$ext) $ext = 'FILE';
                            @endphp
                            <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-[#FAFAF8] transition-colors duration-150 group" role="listitem">

                                {{-- Extension badge --}}
                                <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-[#F5F3F0] border border-[#E8E4DF] flex items-center justify-center font-mono text-[0.62rem] font-bold text-[#B8860B] uppercase tracking-wider leading-none"
                                     aria-label="Format: {{ $ext }}">
                                    {{ $ext }}
                                </div>

                                {{-- Info berkas --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-[#1A1A1A] truncate leading-snug" title="{{ $doc->file_name }}">
                                        {{ $doc->file_name }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.58rem] font-mono font-semibold uppercase tracking-wider text-[#B8860B] bg-[#B8860B]/10">
                                            {{ $doc->document_type }}
                                        </span>
                                        <span class="text-[#D4C4A0] text-[0.60rem]" aria-hidden="true">·</span>
                                        <span class="text-[0.62rem] font-mono text-[#6B6B6B]">
                                            <time datetime="{{ $doc->created_at->format('Y-m-d') }}">{{ $doc->created_at->isoFormat('D MMM YYYY') }}</time>
                                        </span>
                                    </div>
                                </div>

                                {{-- Aksi: Buka & Unduh --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <a href="{{ route('employee.documents.show', $doc) }}"
                                       target="_blank" rel="noopener noreferrer"
                                       class="w-7 h-7 flex items-center justify-center rounded text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF] transition-colors duration-150"
                                       aria-label="Buka dokumen {{ $doc->file_name }} di tab baru">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('employee.documents.download', $doc) }}"
                                       onclick="downloadWithLoading(this, event)"
                                       class="w-7 h-7 flex items-center justify-center rounded text-[#6B6B6B] hover:text-[#B8860B] hover:bg-[#E8E4DF] transition-colors duration-150"
                                       aria-label="Unduh dokumen {{ $doc->file_name }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                        </svg>
                                    </a>
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>

            </section>{{-- /card-serif --}}

        </div>{{-- /kanan --}}

    </div>{{-- /grid --}}

</x-layouts.employee>
