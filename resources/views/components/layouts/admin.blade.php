<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIAPTIKA | Panel Admin">
    <title>{{ $title ?? 'Dashboard' }} | SIAPTIKA Admin</title>
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
            var fromSession = {{ session('just_logged_in') ? 'true' : 'false' }};
            var fromStorage = sessionStorage.getItem('siaptika_show_splash_admin') === 'true';
            window.__shouldShowAdminSplash = fromSession || fromStorage;
        })();
    </script>
    <x-splash mode="Admin" splashId="admin-splash-screen" />
    <script>
        if (!window.__shouldShowAdminSplash) {
            var s = document.getElementById('admin-splash-screen');
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
                container.style.cssText = 'z-index: 99999 !important; position: fixed; top: 1.25rem; left: 50%; transform: translateX(-50%); pointer-events: none; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; width: max-content; max-width: 90vw;';
                document.body.appendChild(container);
            } else {
                container.style.zIndex = '99999';
            }
            var toast = document.createElement('div');
            toast.className = 'toast toast-error';
            toast.setAttribute('role', 'alert');
            toast.style.cssText = 'pointer-events: auto; z-index: 99999 !important; display: flex; align-items: center; justify-between; gap: 0.75rem; background: #FFFFFF; border: 1.5px solid #FCA5A5; color: #991B1B; padding: 0.85rem 1.25rem; border-radius: 0.75rem; box-shadow: 0 10px 30px rgba(153, 27, 27, 0.12); font-family: "IBM Plex Mono", monospace; font-size: 0.75rem; font-weight: 500; max-width: 32rem; width: 100%; animation: toastSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);';
            
            toast.innerHTML = '<div style="display: flex; align-items: center; gap: 0.65rem; flex: 1; min-width: 0;">' +
                '<div style="width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background: #FEF2F2; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #DC2626;">' +
                    '<svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>' +
                '</div>' +
                '<span style="line-height: 1.4; color: #1A1A1A; flex: 1;">' + message + '</span>' +
            '</div>' +
            '<button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #9A948D; padding: 0.25rem; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" onmouseover="this.style.color=\'#DC2626\'" onmouseout="this.style.color=\'#9A948D\'" title="Tutup">' +
                '<svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
            '</button>';

            container.appendChild(toast);
            setTimeout(function () {
                if (toast && toast.parentElement) {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    toast.style.transition = 'all 0.3s ease';
                    setTimeout(function () { if (toast && toast.parentElement) toast.remove(); }, 300);
                }
            }, 5000);
        }

        function armSubmitTimeout(modalId, unlockFn, timeoutMs) {
            timeoutMs = timeoutMs || 20000;
            return setTimeout(function() {
                if (typeof unlockFn === 'function') unlockFn(modalId);
                showToastError('Proses memakan waktu terlalu lama (timeout). Silakan periksa koneksi internet Anda dan coba lagi.');
            }, timeoutMs);
        }

        window.addEventListener('pageshow', resetLoadingStates);
        window.addEventListener('popstate', resetLoadingStates);
    </script>


    <div id="app-content" class="transition-all duration-300">
        {{-- ============================================================
             FIXED HEADER
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
                    <a href="{{ route('admin.dashboard') }}"
                       onclick="__navGo(this, this.href); return false;"
                       class="flex items-center gap-3" style="text-decoration: none;">
                        <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                        <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                        <span class="small-caps" style="font-size: 0.65rem;">Panel Admin</span>
                    </a>
                </div>

                {{-- Right Group: Sync Controls + Nav Links + Profile + Logout --}}
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
                    <nav class="hidden sm:flex items-center gap-1">
                        <a id="nav-dashboard" href="{{ route('admin.dashboard') }}"
                           onclick="__navGo(this, this.href); return false;"
                           class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
                            Dashboard
                        </a>
                        <a id="nav-kegiatan" href="{{ route('admin.activities.index') }}"
                           onclick="__navGo(this, this.href); return false;"
                           class="nav-link-item {{ request()->routeIs('admin.activities.*') ? 'nav-active' : '' }}">
                            Kegiatan
                        </a>
                        <a id="nav-arsip" href="{{ route('admin.archive.index') }}"
                           onclick="__navGo(this, this.href); return false;"
                           class="nav-link-item {{ request()->routeIs('admin.archive.*') ? 'nav-active' : '' }}">
                            Arsip
                        </a>
                    </nav>

                    <span class="h-4 w-px hidden md:block" style="background-color: #E8E4DF;"></span>

                    {{-- User Name & Logout --}}
                    <span class="text-xs hidden md:block" style="color: #6B6B6B; font-family: 'Source Sans 3', system-ui, sans-serif;">
                        {{ Auth::user()->name }}
                    </span>
                    <form id="form-logout" method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button id="btn-logout" type="submit" class="logout-btn" title="Keluar dari Akun">
                            <svg class="logout-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                            <span class="logout-btn-text">Keluar</span>
                        </button>
                    </form>
                </div>

            </nav>
        </header>

        {{-- ============================================================
             TOAST NOTIFICATION CONTAINER
             ============================================================ --}}
        <div class="toast-container" id="toast-container" aria-live="polite">
            @if(session('success'))
                <div class="toast toast-success" role="alert">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="toast toast-error" role="alert">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        {{-- ============================================================
             MAIN CONTENT
             ============================================================ --}}
        <main class="page-main max-w-7xl mx-auto px-6 pb-12">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    {{-- ─── Toast Auto-Dismiss ───────────────────────────────────── --}}
    <script>
        (function () {
            document.querySelectorAll('.toast').forEach(function (toast) {
                setTimeout(function () {
                    toast.classList.add('hiding');
                    setTimeout(function () { toast.remove(); }, 320);
                }, 3600);
            });
        })();
    </script>

    {{-- ─── Global Button Loading Utility ──────────────────────────── --}}
    <script>
        function setButtonLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                var rect = btn.getBoundingClientRect();
                btn.style.width  = rect.width  + 'px';
                btn.style.height = rect.height + 'px';
                btn.dataset.originalContent = btn.innerHTML;
                btn.dataset.loading = 'true';
                btn.disabled = true;
                btn.innerHTML = '<span class="btn-spinner"></span>';
            } else {
                btn.style.width  = '';
                btn.style.height = '';
                btn.dataset.loading = 'false';
                btn.disabled = false;
                if (btn.dataset.originalContent) {
                    btn.innerHTML = btn.dataset.originalContent;
                }
            }
        }
    </script>

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
            
            try { sessionStorage.setItem('siaptika_show_splash_admin', 'true'); } catch (_) {}

            var splash = document.getElementById('admin-splash-screen');
            var progressBar = document.getElementById('admin-splash-screen-bar');
            var statusText  = document.getElementById('admin-splash-screen-status');
            var percentText = document.getElementById('admin-splash-screen-percent');

            if (progressBar) { progressBar.style.transition = 'none'; progressBar.style.width = '0%'; }
            if (percentText) percentText.textContent = '0%';
            if (statusText)  statusText.textContent = 'Menghubungkan ke basis data...';

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

            var logoutForm = document.getElementById('form-logout');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function () {
                    showGlobalLoading('Keluar dari akun…');
                });
            }

            // Splash Screen Admin (Hanya tampil setelah selesai Login / Trigger Manual Sync)
            var splash = document.getElementById('admin-splash-screen');
            var shouldShowSplash = window.__shouldShowAdminSplash;

            if (splash) {
                if (shouldShowSplash) {
                    sessionStorage.removeItem('siaptika_show_splash_admin');
                    var progressBar = document.getElementById('admin-splash-screen-bar');
                    var statusText  = document.getElementById('admin-splash-screen-status');
                    var percentText = document.getElementById('admin-splash-screen-percent');

                    if (progressBar) { progressBar.style.transition = 'none'; progressBar.style.width = '0%'; }
                    if (percentText) percentText.textContent = '0%';
                    if (statusText)  statusText.textContent = 'Menghubungkan ke basis data...';

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
                                else if (progress < 95) statusText.textContent = 'Menyiapkan halaman utama...';
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

    @stack('modals')

</body>
</html>

