<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIAPTIKA | Portal Pegawai Bidang APTIKA">
    <title>{{ $title ?? 'Dashboard' }} | SIAPTIKA Pegawai</title>
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

    <script>
        (function() {
            window.__shouldShowEmployeeSplash = sessionStorage.getItem('siaptika_employee_splash_shown') !== 'true';
        })();
    </script>
    <x-splash mode="Pegawai" splashId="employee-splash-screen" />
    <script>
        if (!window.__shouldShowEmployeeSplash) {
            var s = document.getElementById('employee-splash-screen');
            if (s) s.style.display = 'none';
        }
    </script>

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
                container.style.cssText = 'z-index: 99999 !important; position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%); pointer-events: none; display: flex; flex-direction: column; align-items: center; gap: 0.5rem;';
                document.body.appendChild(container);
            } else {
                container.style.zIndex = '99999';
            }
            var toast = document.createElement('div');
            toast.className = 'toast toast-error';
            toast.setAttribute('role', 'alert');
            toast.style.cssText = 'pointer-events: auto; z-index: 99999 !important;';
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

                {{-- Left Group: Brand Logo --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('employee.dashboard') }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="flex items-center gap-3" style="text-decoration: none;">
                        <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                        <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                        <span class="small-caps" style="font-size: 0.65rem;">Portal Pegawai</span>
                    </a>
                </div>

                {{-- Right Group: Sync Controls + Nav Links --}}
                <div class="flex items-center gap-4">

                    {{-- Refresh Controls (Menempel langsung di sebelah kiri Button Navigasi) --}}
                    <div class="hidden sm:flex items-center gap-2">
                        <span id="sync-timestamp-text" class="text-xs font-mono text-[#6B6B6B] tracking-tight">
                            Disinkronkan --:--:--
                        </span>
                        <button type="button" id="btn-manual-sync" onclick="triggerManualSync(this)" title="Sinkronkan & Muat Ulang Data"
                                class="flex items-center justify-center p-1.5 rounded-lg text-[#6B6B6B] hover:text-[#1A1A1A] hover:bg-[#F5F3F0] transition-all duration-150 cursor-pointer"
                                style="outline: none; border: none; background: none;">
                            <svg id="sync-icon" class="w-4 h-4 text-[#B8860B] transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                        </button>
                    </div>

                    <span class="h-4 w-px hidden sm:block" style="background-color: #E8E4DF;"></span>

                    {{-- Navigation Links (Dashboard, Kegiatan, Arsip) --}}
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
                </div>

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

    {{-- ─── Sync Timestamp & Splash Screen Handler ───────────────── --}}
    <script>
        function getFormattedTime() {
            var now = new Date();
            var hrs  = String(now.getHours()).padStart(2, '0');
            var mins = String(now.getMinutes()).padStart(2, '0');
            var secs = String(now.getSeconds()).padStart(2, '0');
            return hrs + ':' + mins + ':' + secs;
        }

        function updateSyncTimestampDisplay() {
            var currentTime = getFormattedTime();
            try { sessionStorage.setItem('siaptika_last_synced', currentTime); } catch (_) {}
            var el = document.getElementById('sync-timestamp-text');
            if (el) el.textContent = 'Disinkronkan ' + currentTime;
        }

        function triggerManualSync(btn) {
            var icon = document.getElementById('sync-icon');
            if (icon) icon.classList.add('animate-spin');
            
            try { sessionStorage.removeItem('siaptika_employee_splash_shown'); } catch (_) {}

            var splash = document.getElementById('employee-splash-screen');
            var progressBar = document.getElementById('employee-splash-screen-bar');
            var statusText  = document.getElementById('employee-splash-screen-status');
            var percentText = document.getElementById('employee-splash-screen-percent');

            if (progressBar) { progressBar.style.transition = 'none'; progressBar.style.width = '0%'; }
            if (percentText) percentText.textContent = '0%';
            if (statusText)  statusText.textContent = 'Menghubungkan ke server SIAPTIKA...';

            if (splash) {
                splash.style.display = 'flex';
                splash.style.opacity = '1';
                splash.style.pointerEvents = 'all';
            }

            setTimeout(function() {
                window.location.reload();
            }, 80);
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateSyncTimestampDisplay();

            // Splash Screen Mode Pegawai (Tampil saat masuk ke url / sebelum dashboard muncul)
            var splash = document.getElementById('employee-splash-screen');
            var isFirstVisitInSession = sessionStorage.getItem('siaptika_employee_splash_shown') !== 'true';

            if (splash) {
                if (isFirstVisitInSession) {
                    sessionStorage.setItem('siaptika_employee_splash_shown', 'true');
                    var progressBar = document.getElementById('employee-splash-screen-bar');
                    var statusText  = document.getElementById('employee-splash-screen-status');
                    var percentText = document.getElementById('employee-splash-screen-percent');

                    if (progressBar) { progressBar.style.transition = 'none'; progressBar.style.width = '0%'; }
                    if (percentText) percentText.textContent = '0%';
                    if (statusText)  statusText.textContent = 'Menghubungkan ke server SIAPTIKA...';

                    var progress = 0;
                    setTimeout(function() {
                        if (progressBar) progressBar.style.transition = 'width 0.15s ease-out';
                        
                        var interval = setInterval(function() {
                            progress += Math.floor(Math.random() * 12) + 8;
                            if (progress > 100) progress = 100;

                            if (progressBar) progressBar.style.width = progress + '%';
                            if (percentText) percentText.textContent = progress + '%';

                            if (statusText) {
                                if (progress < 30) statusText.textContent = 'Menghubungkan ke sistem...';
                                else if (progress < 65) statusText.textContent = 'Memuat data kegiatan dan dokumen...';
                                else if (progress < 95) statusText.textContent = 'Menyiapkan halaman portal...';
                                else statusText.textContent = 'Sistem siap!';
                            }

                            if (progress >= 100) {
                                clearInterval(interval);
                                setTimeout(function() {
                                    splash.style.opacity = '0';
                                    splash.style.pointerEvents = 'none';
                                    setTimeout(function() {
                                        splash.style.display = 'none';
                                    }, 450);
                                }, 250);
                            }
                        }, 90);
                    }, 50);
                } else {
                    splash.style.display = 'none';
                }
            }
        });
    </script>

</body>
</html>
