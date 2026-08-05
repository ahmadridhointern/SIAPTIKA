<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIAPTIKA — Portal Pegawai Bidang APTIKA">
    <title>{{ $title ?? 'Dashboard' }} — SIAPTIKA Pegawai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Force explicit disabled button styling & cursor */
        button:disabled,
        button[disabled],
        .btn-primary:disabled,
        .btn-primary[disabled],
        .btn-secondary:disabled,
        .btn-secondary[disabled] {
            background-color: #E2E8F0 !important;
            background-image: none !important;
            color: #94A3B8 !important;
            border: 1px solid #CBD5E1 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            opacity: 0.75 !important;
            transform: none !important;
        }
        button:disabled *,
        button[disabled] *,
        .btn-primary:disabled *,
        .btn-primary[disabled] * {
            cursor: not-allowed !important;
        }
    </style>
</head>
<body style="background-color: #FAFAF8; min-height: 100vh;">

    {{-- ─── Global Loading Overlay ──────────────────────────────── --}}
    <div id="global-loading-overlay"
         style="display:none; position:fixed; inset:0; z-index:9999;
                background:rgba(26,26,26,0.30);
                backdrop-filter:blur(3px); -webkit-backdrop-filter:blur(3px);
                align-items:center; justify-content:center;">
        <div style="background:#FFFFFF; border-radius:1rem; padding:2rem 2.5rem;
                    box-shadow:0 8px 32px rgba(0,0,0,0.12);
                    display:flex; flex-direction:column; align-items:center; gap:1rem;
                    min-width:11rem;">
            <span style="display:inline-block; width:2.5rem; height:2.5rem;
                         border:3px solid rgba(184,134,11,0.18);
                         border-top-color:#B8860B; border-radius:50%;
                         animation:btnSpin 0.75s linear infinite;"></span>
            <span id="global-loading-text"
                  style="font-family:'Source Mono',monospace; font-size:0.72rem;
                         color:#6B6B6B; text-align:center; line-height:1.4;">
                Memproses…
            </span>
        </div>
    </div>

    {{-- ─── Nav-link loading spinner helper ─────────────────────── --}}
    <script>
        function __navGo(el, href) {
            if (el.dataset.loading === 'true') return;
            var dest;
            try { dest = new URL(href, window.location.origin); }
            catch (_) { window.location.href = href; return; }

            var destPath = dest.pathname.replace(/\/$/, '');
            var currPath = window.location.pathname.replace(/\/$/, '');
            if (destPath === currPath && dest.search === window.location.search) return;

            var rect = el.getBoundingClientRect();
            el.style.width          = rect.width  + 'px';
            el.style.height         = rect.height + 'px';
            el.style.display        = 'inline-flex';
            el.style.alignItems     = 'center';
            el.style.justifyContent = 'center';
            el.style.pointerEvents  = 'none';
            el.dataset.loading      = 'true';

            el.innerHTML =
                '<span style="' +
                    'display:inline-block;' +
                    'width:0.8rem;height:0.8rem;' +
                    'border:2px solid rgba(184,134,11,0.25);' +
                    'border-top-color:#B8860B;' +
                    'border-radius:50%;' +
                    'animation:btnSpin 0.65s linear infinite;' +
                '"></span>';

            window.location.href = dest.href;
        }
    </script>

    {{-- ─── Global Loading Overlay Utilities ────────────────────── --}}
    <script>
        function showGlobalLoading(msg) {
            var el  = document.getElementById('global-loading-overlay');
            var txt = document.getElementById('global-loading-text');
            if (txt) txt.textContent = msg || 'Memproses…';
            if (el)  el.style.display = 'flex';
        }
        function hideGlobalLoading() {
            var el = document.getElementById('global-loading-overlay');
            if (el) el.style.display = 'none';
        }
        function resetLoadingStates() {
            hideGlobalLoading();
            document.querySelectorAll('[data-loading="true"]').forEach(function (el) {
                if (typeof el.dataset.originalContent !== 'undefined' && el.dataset.originalContent !== '') {
                    el.innerHTML = el.dataset.originalContent;
                }
                el.dataset.loading = 'false';
                el.style.width = '';
                el.style.height = '';
                el.style.pointerEvents = '';
                el.disabled = false;
            });
        }
        function downloadWithLoading(el, e) {
            e.preventDefault();
            showGlobalLoading('Mengunduh berkas…');
            setTimeout(function() { hideGlobalLoading(); }, 3000);
            window.location.href = el.href;
        }

        function showToastError(message) {
            var container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container';
                container.id = 'toast-container';
                container.setAttribute('aria-live', 'polite');
                document.body.appendChild(container);
            }
            var toast = document.createElement('div');
            toast.className = 'toast toast-error';
            toast.setAttribute('role', 'alert');
            toast.innerHTML = '<svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">' +
                '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>' +
                '</svg><span>' + message + '</span>';
            container.appendChild(toast);
            setTimeout(function () {
                toast.classList.add('hiding');
                setTimeout(function () { toast.remove(); }, 320);
            }, 4500);
        }

        function armSubmitTimeout(modalId, unlockFn, timeoutMs) {
            timeoutMs = timeoutMs || 20000;
            return setTimeout(function() {
                if (typeof unlockFn === 'function') unlockFn(modalId);
                showToastError('Proses memakan waktu terlalu lama (timeout). Silakan periksa koneksi internet Anda dan coba lagi.');
            }, timeoutMs);
        }

        /* Otomatis bersihkan state loading jika pengguna menekan tombol Back / Forward di browser */
        window.addEventListener('pageshow', resetLoadingStates);
        window.addEventListener('popstate', resetLoadingStates);
    </script>

    <div id="app-content" class="transition-all duration-300">

        {{-- ============================================================
             FIXED HEADER — Portal Pegawai (tanpa auth)
             ============================================================ --}}
        <header style="
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            height: var(--header-height, 4rem);
            background-color: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(232, 228, 223, 0.75);
            box-shadow: 0 1px 8px rgba(26, 26, 26, 0.05);
        ">
            <nav class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">

                {{-- Brand --}}
                <a href="{{ route('employee.dashboard') }}"
                   onclick="__navGo(this, this.href); return false;"
                   class="flex items-center gap-3" style="text-decoration: none;">
                    <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                    <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                    <span class="small-caps" style="font-size: 0.65rem;">Portal Pegawai</span>
                </a>

                {{-- Navigation Links --}}
                <nav class="flex items-center gap-1">
                    <a id="nav-dashboard"
                       href="{{ route('employee.dashboard') }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="nav-link-item {{ request()->routeIs('employee.dashboard') ? 'nav-active' : '' }}">
                        Dashboard
                    </a>
                    <a id="nav-kegiatan"
                       href="{{ route('employee.activities.index') }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="nav-link-item {{ request()->routeIs('employee.activities.*') ? 'nav-active' : '' }}">
                        Kegiatan
                    </a>
                    <a id="nav-arsip"
                       href="{{ route('employee.archive.index') }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="nav-link-item {{ request()->routeIs('employee.archive.*') ? 'nav-active' : '' }}">
                        Arsip
                    </a>
                </nav>

            </nav>
        </header>

        {{-- ============================================================
             MAIN CONTENT
             ============================================================ --}}
        <main class="page-main max-w-7xl mx-auto px-6 pb-12">
            {{ $slot }}
        </main>

    </div>

    @stack('modals')

</body>
</html>
