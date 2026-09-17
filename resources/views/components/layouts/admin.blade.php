<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        function showToastSuccess(message) {
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
            toast.className = 'toast toast-success';
            toast.setAttribute('role', 'alert');
            toast.style.cssText = 'pointer-events: auto; z-index: 99999 !important; display: flex; align-items: center; justify-between; gap: 0.75rem; background: #FFFFFF; border: 1.5px solid #86EFAC; color: #166534; padding: 0.85rem 1.25rem; border-radius: 0.75rem; box-shadow: 0 10px 30px rgba(22, 101, 52, 0.10); font-family: "IBM Plex Mono", monospace; font-size: 0.75rem; font-weight: 500; max-width: 32rem; width: 100%; animation: toastSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);';
            
            toast.innerHTML = '<div style="display: flex; align-items: center; gap: 0.65rem; flex: 1; min-width: 0;">' +
                '<div style="width: 1.75rem; height: 1.75rem; border-radius: 0.5rem; background: #F0FDF4; border: 1px solid #BBF7D0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #16A34A;">' +
                    '<svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
                '</div>' +
                '<span style="line-height: 1.4; color: #1A1A1A; flex: 1;">' + message + '</span>' +
            '</div>' +
            '<button type="button" onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #9A948D; padding: 0.25rem; border-radius: 0.375rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" onmouseover="this.style.color=\'#16A34A\'" onmouseout="this.style.color=\'#9A948D\'" title="Tutup">' +
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
                    <nav class="flex items-center gap-1">
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

                    {{-- Settings Button --}}
                    <button type="button"
                            id="btn-settings"
                            onclick="openSettingsModal()"
                            class="settings-btn"
                            title="Pengaturan Akun">
                        <svg class="settings-btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="settings-btn-text">Pengaturan</span>
                    </button>

                    {{-- Logout Button --}}
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

    {{-- ─── Settings Modal — Ganti ID / Password ──────────────── --}}
    <div id="settings-modal-overlay"
         onclick="if(event.target===this && !__isSettingsLocked) closeSettingsModal()"
         style="display:none; position:fixed; inset:0; z-index:200;
                background:rgba(26,26,26,0.40);
                backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px);
                align-items:center; justify-content:center; padding:1rem;">

        <div id="settings-modal-box"
             style="background:#FFFFFF; border-radius:1rem; width:100%; max-width:26rem;
                    box-shadow:0 12px 40px rgba(26,26,26,0.14);
                    border:1px solid #E8E4DF;
                    position:relative;
                    transform:translateY(20px); opacity:0;
                    transition:transform 0.25s ease, opacity 0.25s ease;">

            {{-- Modal Header --}}
            <div style="display:flex; align-items:center; justify-content:space-between;
                         padding:1.25rem 1.5rem 0;">
                <div>
                    <h2 style="font-family:'Playfair Display',Georgia,serif; font-size:1.125rem;
                                color:#1A1A1A; margin:0; line-height:1.3;">Pengaturan Akun</h2>
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:0.65rem;
                               color:#B8860B; letter-spacing:0.12em; text-transform:uppercase;
                               margin:0.2rem 0 0;">Administrator</p>
                </div>
                <button type="button" id="btn-settings-close" onclick="closeSettingsModal()"
                        style="background:none; border:none; cursor:pointer; color:#9A948D;
                               padding:0.375rem; border-radius:0.375rem; display:flex;
                               align-items:center; transition:color 0.15s;"
                        onmouseover="this.style.color='#1A1A1A'" onmouseout="this.style.color='#9A948D'"
                        title="Tutup">
                    <svg style="width:1.1rem;height:1.1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Divider --}}
            <div style="height:1px; background:#E8E4DF; margin:1rem 1.5rem 0;"></div>

            {{-- Slider Tab --}}
            <div style="padding:1rem 1.5rem 0;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.375rem;
                             background:#F5F3F0; padding:0.3rem; border-radius:0.625rem;">
                    <button type="button" id="tab-btn-login-id"
                            onclick="switchSettingsTab('login_id')"
                            style="padding:0.5rem; border-radius:0.375rem; border:none; cursor:pointer;
                                   font-family:'IBM Plex Mono',monospace; font-size:0.7rem; font-weight:600;
                                   letter-spacing:0.05em; text-transform:uppercase;
                                   transition:all 0.2s ease;
                                   background:#B8860B; color:#FFFFFF;
                                   box-shadow:0 1px 4px rgba(184,134,11,0.20);">
                        Ubah ID
                    </button>
                    <button type="button" id="tab-btn-password"
                            onclick="switchSettingsTab('password')"
                            style="padding:0.5rem; border-radius:0.375rem; border:none; cursor:pointer;
                                   font-family:'IBM Plex Mono',monospace; font-size:0.7rem; font-weight:600;
                                   letter-spacing:0.05em; text-transform:uppercase;
                                   transition:all 0.2s ease;
                                   background:transparent; color:#6B6B6B;
                                   box-shadow:none;">
                        Ubah Password
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form id="form-settings-credentials"
                  style="padding:1.25rem 1.5rem 1.5rem;"
                  onsubmit="submitSettingsForm(event)">
                @csrf

                {{-- Hidden mode field --}}
                <input type="hidden" id="settings-type" name="type" value="login_id">

                {{-- Error Banner --}}
                <div id="settings-error-banner"
                     style="display:none; background:#FEF2F2; border:1px solid #FECACA;
                             border-radius:0.5rem; padding:0.65rem 0.875rem; margin-bottom:1rem;
                             font-family:'IBM Plex Mono',monospace; font-size:0.72rem; color:#DC2626;
                             line-height:1.5;">
                </div>

                {{-- Password Saat Ini (always visible) --}}
                <div style="margin-bottom:1rem;">
                    <label for="settings-current-password"
                           style="display:block; font-family:'IBM Plex Mono',monospace;
                                  font-size:0.67rem; font-weight:600; color:#6B6B6B;
                                  letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">
                        Password Saat Ini
                    </label>
                    <div style="position:relative;">
                        <input type="password" id="settings-current-password" name="current_password"
                               autocomplete="current-password"
                               style="width:100%; box-sizing:border-box; height:2.75rem;
                                      border:1px solid #E8E4DF; border-radius:0.5rem;
                                      padding:0 2.5rem 0 0.875rem;
                                      font-family:'Source Sans 3',system-ui,sans-serif;
                                      font-size:0.9rem; color:#1A1A1A; background:transparent;
                                      outline:none; transition:border-color 0.15s, box-shadow 0.15s;"
                               onfocus="this.style.borderColor='#B8860B'; this.style.boxShadow='0 0 0 3px rgba(184,134,11,0.12)'"
                               onblur="this.style.borderColor='#E8E4DF'; this.style.boxShadow='none'"
                               placeholder="Masukkan password saat ini">
                        <button type="button"
                                onclick="togglePasswordVisibility('settings-current-password', this)"
                                style="position:absolute; right:0.625rem; top:50%; transform:translateY(-50%);
                                       background:none; border:none; cursor:pointer; color:#9A948D; padding:0.25rem;">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Panel: Ubah ID --}}
                <div id="settings-panel-login-id">
                    <div style="margin-bottom:1.25rem;">
                        <label for="settings-new-login-id"
                               style="display:block; font-family:'IBM Plex Mono',monospace;
                                      font-size:0.67rem; font-weight:600; color:#6B6B6B;
                                      letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">
                            ID Administrator Baru
                        </label>
                        <input type="text" id="settings-new-login-id" name="new_login_id"
                               autocomplete="off"
                               style="width:100%; box-sizing:border-box; height:2.75rem;
                                      border:1px solid #E8E4DF; border-radius:0.5rem;
                                      padding:0 0.875rem;
                                      font-family:'Source Sans 3',system-ui,sans-serif;
                                      font-size:0.9rem; color:#1A1A1A; background:transparent;
                                      outline:none; transition:border-color 0.15s, box-shadow 0.15s;"
                               onfocus="this.style.borderColor='#B8860B'; this.style.boxShadow='0 0 0 3px rgba(184,134,11,0.12)'"
                               onblur="this.style.borderColor='#E8E4DF'; this.style.boxShadow='none'"
                               placeholder="Minimal 3 karakter">
                    </div>
                </div>

                {{-- Panel: Ubah Password --}}
                <div id="settings-panel-password" style="display:none;">
                    <div style="margin-bottom:1rem;">
                        <label for="settings-new-password"
                               style="display:block; font-family:'IBM Plex Mono',monospace;
                                      font-size:0.67rem; font-weight:600; color:#6B6B6B;
                                      letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">
                            Password Baru
                        </label>
                        <div style="position:relative;">
                            <input type="password" id="settings-new-password" name="new_password"
                                   autocomplete="new-password"
                                   style="width:100%; box-sizing:border-box; height:2.75rem;
                                          border:1px solid #E8E4DF; border-radius:0.5rem;
                                          padding:0 2.5rem 0 0.875rem;
                                          font-family:'Source Sans 3',system-ui,sans-serif;
                                          font-size:0.9rem; color:#1A1A1A; background:transparent;
                                          outline:none; transition:border-color 0.15s, box-shadow 0.15s;"
                                   onfocus="this.style.borderColor='#B8860B'; this.style.boxShadow='0 0 0 3px rgba(184,134,11,0.12)'"
                                   onblur="this.style.borderColor='#E8E4DF'; this.style.boxShadow='none'"
                                   placeholder="Minimal 8 karakter">
                            <button type="button"
                                    onclick="togglePasswordVisibility('settings-new-password', this)"
                                    style="position:absolute; right:0.625rem; top:50%; transform:translateY(-50%);
                                           background:none; border:none; cursor:pointer; color:#9A948D; padding:0.25rem;">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div style="margin-bottom:1.25rem;">
                        <label for="settings-new-password-confirmation"
                               style="display:block; font-family:'IBM Plex Mono',monospace;
                                      font-size:0.67rem; font-weight:600; color:#6B6B6B;
                                      letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.4rem;">
                            Konfirmasi Password Baru
                        </label>
                        <div style="position:relative;">
                            <input type="password" id="settings-new-password-confirmation"
                                   name="new_password_confirmation"
                                   autocomplete="new-password"
                                   style="width:100%; box-sizing:border-box; height:2.75rem;
                                          border:1px solid #E8E4DF; border-radius:0.5rem;
                                          padding:0 2.5rem 0 0.875rem;
                                          font-family:'Source Sans 3',system-ui,sans-serif;
                                          font-size:0.9rem; color:#1A1A1A; background:transparent;
                                          outline:none; transition:border-color 0.15s, box-shadow 0.15s;"
                                   onfocus="this.style.borderColor='#B8860B'; this.style.boxShadow='0 0 0 3px rgba(184,134,11,0.12)'"
                                   onblur="this.style.borderColor='#E8E4DF'; this.style.boxShadow='none'"
                                   placeholder="Ulangi password baru">
                            <button type="button"
                                    onclick="togglePasswordVisibility('settings-new-password-confirmation', this)"
                                    style="position:absolute; right:0.625rem; top:50%; transform:translateY(-50%);
                                           background:none; border:none; cursor:pointer; color:#9A948D; padding:0.25rem;">
                                <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="btn-settings-submit"
                        class="btn-primary w-full justify-center"
                        style="min-height:2.75rem; border-radius:0.5rem; font-family:'IBM Plex Mono',monospace; font-size:0.72rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;"
                        disabled>
                    <span id="btn-settings-submit-text">Simpan Perubahan</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ─── Settings Modal JS ──────────────────────────────────── --}}
    <script>
        var __currentSettingsTab = 'login_id';
        var __isSettingsLocked = false;
        var __settingsTimeoutTimer = null;

        function openSettingsModal() {
            if (__isSettingsLocked) return;
            var overlay = document.getElementById('settings-modal-overlay');
            var box     = document.getElementById('settings-modal-box');
            resetSettingsForm();
            overlay.style.display = 'flex';
            requestAnimationFrame(function() {
                box.style.transform = 'translateY(0)';
                box.style.opacity   = '1';
            });
        }

        function closeSettingsModal() {
            if (__isSettingsLocked) return;
            var overlay = document.getElementById('settings-modal-overlay');
            var box     = document.getElementById('settings-modal-box');
            box.style.transform = 'translateY(20px)';
            box.style.opacity   = '0';
            setTimeout(function() {
                overlay.style.display = 'none';
            }, 250);
        }

        function switchSettingsTab(tab) {
            if (__isSettingsLocked) return;
            __currentSettingsTab = tab;
            document.getElementById('settings-type').value = tab;

            var tabLoginId  = document.getElementById('tab-btn-login-id');
            var tabPassword = document.getElementById('tab-btn-password');
            var panelLoginId  = document.getElementById('settings-panel-login-id');
            var panelPassword = document.getElementById('settings-panel-password');

            var activeStyle   = { background: '#B8860B', color: '#FFFFFF', boxShadow: '0 1px 4px rgba(184,134,11,0.20)' };
            var inactiveStyle = { background: 'transparent', color: '#6B6B6B', boxShadow: 'none' };

            if (tab === 'login_id') {
                Object.assign(tabLoginId.style,  activeStyle);
                Object.assign(tabPassword.style, inactiveStyle);
                panelLoginId.style.display  = 'block';
                panelPassword.style.display = 'none';
            } else {
                Object.assign(tabPassword.style, activeStyle);
                Object.assign(tabLoginId.style,  inactiveStyle);
                panelPassword.style.display = 'block';
                panelLoginId.style.display  = 'none';
            }

            // Clear error banner on tab switch & revalidate
            document.getElementById('settings-error-banner').style.display = 'none';
            validateSettingsForm();
        }

        function validateSettingsForm() {
            if (__isSettingsLocked) return;
            var type = document.getElementById('settings-type') ? document.getElementById('settings-type').value : 'login_id';
            var currentPasswordEl = document.getElementById('settings-current-password');
            var currentPassword = currentPasswordEl ? (currentPasswordEl.value || '').trim() : '';
            var submitBtn = document.getElementById('btn-settings-submit');
            var isValid = false;

            if (currentPassword.length > 0) {
                if (type === 'login_id') {
                    var newLoginIdEl = document.getElementById('settings-new-login-id');
                    var newLoginId = newLoginIdEl ? (newLoginIdEl.value || '').trim() : '';
                    if (newLoginId.length >= 3) {
                        isValid = true;
                    }
                } else if (type === 'password') {
                    var newPasswordEl = document.getElementById('settings-new-password');
                    var confirmPasswordEl = document.getElementById('settings-new-password-confirmation');
                    var newPassword = newPasswordEl ? (newPasswordEl.value || '') : '';
                    var confirmPassword = confirmPasswordEl ? (confirmPasswordEl.value || '') : '';
                    if (newPassword.length >= 8 && confirmPassword.length >= 8 && newPassword === confirmPassword) {
                        isValid = true;
                    }
                }
            }

            if (submitBtn && submitBtn.dataset.loading !== 'true') {
                submitBtn.disabled = !isValid;
            }
        }

        function resetSettingsForm() {
            var form = document.getElementById('form-settings-credentials');
            if (form) form.reset();
            var banner = document.getElementById('settings-error-banner');
            if (banner) banner.style.display = 'none';
            switchSettingsTab('login_id');
            validateSettingsForm();
        }

        function lockSettingsModal() {
            __isSettingsLocked = true;
            var box = document.getElementById('settings-modal-box');
            if (box) {
                var ol = document.getElementById('settings-modal-lock-overlay');
                if (!ol) {
                    ol = document.createElement('div');
                    ol.id = 'settings-modal-lock-overlay';
                    ol.style.cssText = 'position:absolute;inset:0;background:rgba(255,255,255,0.45);border-radius:inherit;z-index:30;cursor:not-allowed;pointer-events:all;';
                    box.appendChild(ol);
                }
            }

            // Disable modal close & tabs
            var closeBtn = document.getElementById('btn-settings-close');
            if (closeBtn) closeBtn.style.pointerEvents = 'none';
            var tab1 = document.getElementById('tab-btn-login-id');
            if (tab1) tab1.style.pointerEvents = 'none';
            var tab2 = document.getElementById('tab-btn-password');
            if (tab2) tab2.style.pointerEvents = 'none';

            var btn = document.getElementById('btn-settings-submit');
            var btnTxt = document.getElementById('btn-settings-submit-text');
            if (btn) {
                btn.disabled = true;
                btn.dataset.loading = 'true';
                btn.style.pointerEvents = 'none';
                btnTxt.innerHTML = '<span style="display:inline-flex;align-items:center;justify-content:center;width:1.1rem;height:1.1rem;border:2.5px solid #FFFFFF;border-top-color:transparent;border-radius:50%;animation:btnSpin 0.65s linear infinite;margin:0 auto;"></span>';
            }
        }

        function unlockSettingsModal() {
            __isSettingsLocked = false;
            var ol = document.getElementById('settings-modal-lock-overlay');
            if (ol) ol.remove();

            var closeBtn = document.getElementById('btn-settings-close');
            if (closeBtn) closeBtn.style.pointerEvents = '';
            var tab1 = document.getElementById('tab-btn-login-id');
            if (tab1) tab1.style.pointerEvents = '';
            var tab2 = document.getElementById('tab-btn-password');
            if (tab2) tab2.style.pointerEvents = '';

            var btn = document.getElementById('btn-settings-submit');
            var btnTxt = document.getElementById('btn-settings-submit-text');
            if (btn) {
                btn.dataset.loading = 'false';
                btn.style.pointerEvents = '';
                btnTxt.textContent = 'Simpan Perubahan';
            }
            validateSettingsForm();
        }

        function togglePasswordVisibility(fieldId, btn) {
            if (__isSettingsLocked) return;
            var field = document.getElementById(fieldId);
            if (!field) return;
            if (field.type === 'password') {
                field.type = 'text';
                btn.querySelector('svg').innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>';
            } else {
                field.type = 'password';
                btn.querySelector('svg').innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>';
            }
        }

        function showSettingsError(message) {
            var banner = document.getElementById('settings-error-banner');
            banner.innerHTML = message;
            banner.style.display = 'block';
        }

        function submitSettingsForm(e) {
            e.preventDefault();
            if (__isSettingsLocked) return;

            var form = document.getElementById('form-settings-credentials');
            document.getElementById('settings-error-banner').style.display = 'none';

            lockSettingsModal();

            // Arm submit timeout (20s)
            if (__settingsTimeoutTimer) clearTimeout(__settingsTimeoutTimer);
            __settingsTimeoutTimer = setTimeout(function() {
                unlockSettingsModal();
                showSettingsError('Waktu permintaan habis (timeout). Silakan periksa koneksi internet Anda dan coba lagi.');
            }, 20000);

            var formData = new FormData(form);
            formData.append('_method', 'PUT');

            var csrfToken = document.querySelector('meta[name="csrf-token"]')
                ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                : formData.get('_token');

            fetch('{{ route('admin.settings.credentials') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(function(res) {
                return res.text().then(function(text) {
                    var data = null;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {}
                    return { ok: res.ok, status: res.status, data: data, rawText: text };
                });
            })
            .then(function(result) {
                if (__settingsTimeoutTimer) {
                    clearTimeout(__settingsTimeoutTimer);
                    __settingsTimeoutTimer = null;
                }

                if (result.ok && result.data && result.data.success) {
                    unlockSettingsModal();
                    closeSettingsModal();
                    try {
                        showToastSuccess(result.data.message || 'Perubahan berhasil disimpan.');
                    } catch (_) {}
                    return;
                }

                unlockSettingsModal();

                if (result.data) {
                    if (result.data.errors && typeof result.data.errors === 'object') {
                        var msgs = [];
                        for (var key in result.data.errors) {
                            if (result.data.errors.hasOwnProperty(key)) {
                                var fieldErrors = result.data.errors[key];
                                if (Array.isArray(fieldErrors)) {
                                    msgs.push.apply(msgs, fieldErrors);
                                } else if (typeof fieldErrors === 'string') {
                                    msgs.push(fieldErrors);
                                }
                            }
                        }
                        if (msgs.length > 0) {
                            showSettingsError(msgs.join('<br>'));
                            return;
                        }
                    }
                    if (result.data.message) {
                        showSettingsError(result.data.message);
                        return;
                    }
                }

                if (result.status === 419) {
                    showSettingsError('Sesi telah berakhir. Silakan muat ulang halaman.');
                    return;
                }

                showSettingsError('Terjadi kesalahan pada server (Status ' + result.status + '). Silakan coba lagi.');
            })
            .catch(function(err) {
                if (__settingsTimeoutTimer) {
                    clearTimeout(__settingsTimeoutTimer);
                    __settingsTimeoutTimer = null;
                }
                unlockSettingsModal();
                showSettingsError('Gagal menghubungi server. Periksa koneksi internet Anda.');
            });
        }

        // Real-time input validation listeners
        document.addEventListener('DOMContentLoaded', function() {
            ['settings-current-password', 'settings-new-login-id', 'settings-new-password', 'settings-new-password-confirmation'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', validateSettingsForm);
                    el.addEventListener('keyup', validateSettingsForm);
                    el.addEventListener('change', validateSettingsForm);
                }
            });
            validateSettingsForm();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !__isSettingsLocked) closeSettingsModal();
        });
    </script>

    @stack('modals')


</body>
</html>

