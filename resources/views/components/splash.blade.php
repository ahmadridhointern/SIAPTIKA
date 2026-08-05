{{-- Component: Splash Screen (Admin & Pegawai) --}}
@props([
    'mode' => 'Admin', // 'Admin' atau 'Pegawai'
    'splashId' => 'siaptika-splash-screen'
])

<div id="{{ $splashId }}"
     style="position: fixed; inset: 0; z-index: 999999;
            background-color: #FAFAF8;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            color: #1A1A1A; font-family: 'Source Sans 3', system-ui, sans-serif;
            transition: opacity 0.35s ease-out;
            user-select: none;">

    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; max-width: 280px; width: 85%;">
        
        {{-- Brand Title --}}
        <h1 style="font-family: 'Instrument Serif', Georgia, serif; font-size: 2.5rem; font-weight: 400;
                   letter-spacing: -0.02em; color: #1A1A1A; margin: 0 0 0.25rem 0; line-height: 1.1;">
            SIAPTIKA
        </h1>
        
        {{-- Subtitle Label --}}
        <div style="display: flex; items-center; gap: 0.5rem; margin-bottom: 2rem;">
            <span style="font-family: 'Source Mono', monospace; font-size: 0.65rem; text-transform: uppercase;
                         letter-spacing: 0.12em; color: #6B6B6B;">
                {{ $mode === 'Admin' ? 'Panel Admin' : 'Portal Pegawai' }}
            </span>
        </div>

        {{-- Minimal Line Progress Bar --}}
        <div style="width: 100%; background-color: #E8E4DF; border-radius: 999px;
                    height: 3px; overflow: hidden; margin-bottom: 0.85rem; position: relative;">
            <div id="{{ $splashId }}-bar"
                 style="width: 0%; height: 100%; background-color: #B8860B;
                        border-radius: 999px; transition: width 0.15s ease-out;"></div>
        </div>

        {{-- Status Info & Percentage --}}
        <div style="width: 100%; display: flex; align-items: center; justify-content: space-between;
                    font-family: 'Source Mono', monospace; font-size: 0.7rem; color: #6B6B6B;">
            <span id="{{ $splashId }}-status" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 80%;">
                Memuat data...
            </span>
            <span id="{{ $splashId }}-percent" style="font-weight: 600; color: #1A1A1A;">
                0%
            </span>
        </div>
    </div>
</div>
