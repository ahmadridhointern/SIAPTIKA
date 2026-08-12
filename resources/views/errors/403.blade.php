<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>403 — Akses Ditolak | SIAPTIKA</title>
    @vite(['resources/css/app.css'])
</head>
<body style="background-color: #FAFAF8; min-height: 100vh; display: flex; flex-direction: column;">

    {{-- ─── Topbar ───────────────────────────────────────────────────────── --}}
    <header style="
        position: fixed;
        top: 0; left: 0; right: 0;
        z-index: 100;
        height: 4rem;
        background-color: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(232, 228, 223, 0.75);
        box-shadow: 0 1px 8px rgba(26, 26, 26, 0.05);
    ">
        <nav class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('employee.dashboard') }}"
                   class="flex items-center gap-3" style="text-decoration: none;">
                    <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                    <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                    <span class="small-caps" style="font-size: 0.65rem;">Sistem Informasi Administrasi</span>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('admin.dashboard') : route('employee.dashboard') }}"
                   class="nav-link-item" style="font-size: 0.8rem;">
                    ← Kembali ke Beranda
                </a>
            </div>
        </nav>
    </header>

    {{-- ─── Main Content ──────────────────────────────────────────────────── --}}
    <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding-top: 4rem; padding: 4rem 1.5rem 2rem;">
        <div style="text-align: center; max-width: 36rem; width: 100%;">

            {{-- Error Code --}}
            <div style="margin-bottom: 1.5rem;">
                <span style="
                    font-family: 'Source Mono', 'IBM Plex Mono', monospace;
                    font-size: clamp(5rem, 15vw, 8rem);
                    font-weight: 700;
                    color: #E8E4DF;
                    letter-spacing: -0.05em;
                    line-height: 1;
                    display: block;
                    user-select: none;
                ">403</span>
            </div>

            {{-- Divider --}}
            <div style="width: 3rem; height: 2px; background: linear-gradient(90deg, #B8860B, #D4A017); margin: 0 auto 2rem; border-radius: 1px;"></div>

            {{-- Heading --}}
            <h1 class="font-serif" style="font-size: 1.5rem; color: #1A1A1A; margin-bottom: 0.75rem; font-weight: 600;">
                Akses Ditolak
            </h1>

            {{-- Description --}}
            <p style="font-size: 0.875rem; color: #6B6B6B; line-height: 1.7; margin-bottom: 2.5rem; font-family: 'Source Sans 3', system-ui, sans-serif;">
                Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Silakan login dengan akun yang memiliki akses yang sesuai.
            </p>

            {{-- Action Buttons --}}
            <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                @if(auth()->check())
                    <a href="{{ route('admin.dashboard') }}"
                       class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Login sebagai Admin
                    </a>
                @endif
                <button onclick="history.back()" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                    Halaman Sebelumnya
                </button>
            </div>

            {{-- Error Reference --}}
            <p style="margin-top: 3rem; font-family: 'Source Mono', monospace; font-size: 0.65rem; color: #C0BAB2; letter-spacing: 0.05em; text-transform: uppercase;">
                SIAPTIKA &nbsp;·&nbsp; Error 403 &nbsp;·&nbsp; Bidang APTIKA
            </p>
        </div>
    </main>

</body>
</html>
