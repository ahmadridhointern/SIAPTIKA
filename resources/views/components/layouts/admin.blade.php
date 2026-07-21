<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIAPTIKA — Panel Admin">
    <title>{{ $title ?? 'Dashboard' }} — SIAPTIKA Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #FAFAF8; min-height: 100vh;">

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

                {{-- Brand --}}
                <a href="{{ route('admin.dashboard') }}" data-page-name="Dashboard"
                   class="flex items-center gap-3" style="text-decoration: none;">
                    <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                    <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                    <span class="small-caps" style="font-size: 0.65rem;">Panel Admin</span>
                </a>

                {{-- Nav Links + Logout --}}
                <div class="flex items-center gap-5">

                    {{-- Navigation Links --}}
                    <nav class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}" data-page-name="Dashboard"
                           class="px-3 py-1.5 rounded text-xs font-mono font-medium transition-colors duration-150"
                           style="color: {{ request()->routeIs('admin.dashboard') ? '#B8860B' : '#6B6B6B' }}; background: {{ request()->routeIs('admin.dashboard') ? 'rgba(184,134,11,0.08)' : 'transparent' }};">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.activities.index') }}" data-page-name="Kegiatan"
                           class="px-3 py-1.5 rounded text-xs font-mono font-medium transition-colors duration-150"
                           style="color: {{ request()->routeIs('admin.activities.*') ? '#B8860B' : '#6B6B6B' }}; background: {{ request()->routeIs('admin.activities.*') ? 'rgba(184,134,11,0.08)' : 'transparent' }};">
                            Kegiatan
                        </a>
                    </nav>

                    <span class="h-4 w-px hidden sm:block" style="background-color: #E8E4DF;"></span>

                    {{-- User name (hidden on mobile) --}}
                    <span class="text-xs hidden md:block" style="color: #6B6B6B; font-family: 'Source Sans 3', system-ui, sans-serif;">
                        {{ Auth::user()->name }}
                    </span>

                    {{-- Logout Button — Distinct pill style --}}
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
             MAIN CONTENT (with top padding for fixed header)
             ============================================================ --}}
        <main class="page-main max-w-7xl mx-auto px-6 pb-12">
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    {{-- ─── Page Transition Overlay ──────────────────────────────── --}}
    <div id="page-transition-overlay" aria-hidden="true" aria-live="assertive">
        <div class="page-transition-spinner"></div>
        <p class="page-transition-label" id="page-transition-label">Mengarahkan...</p>
    </div>

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

    {{-- ─── Page Transition Interceptor ─────────────────────────── --}}
    {{--
        Strategy: preventDefault on nav-link click → show overlay → wait 250ms
        (enough for the CSS opacity transition to render) → navigate via
        window.location.href.  This guarantees the overlay is visible even
        when the destination page loads very fast (e.g. localhost dashboard).
    --}}
    <script>
        (function () {
            var overlay = document.getElementById('page-transition-overlay');
            var label   = document.getElementById('page-transition-label');

            /* ── Helpers ─────────────────────────────────────────────── */
            function pageName(pathname) {
                var p = pathname.replace(/\/$/, '');
                if (p === '/admin/dashboard')                    return 'Dashboard';
                if (/^\/admin\/activities\/\d+\/edit$/.test(p)) return 'Edit Kegiatan';
                if (/^\/admin\/activities\/\d+$/.test(p))       return 'Detail Kegiatan';
                if (/^\/admin\/activities/.test(p))              return 'Kegiatan';
                if (/^\/admin/.test(p))                         return 'Dashboard';
                return 'Halaman Berikutnya';
            }

            function showOverlay(name) {
                if (!overlay || !label) return;
                label.textContent = 'Mengarahkan ke Halaman ' + name;
                overlay.setAttribute('aria-hidden', 'false');
                overlay.classList.add('active');
            }

            function hideOverlay() {
                if (!overlay) return;
                overlay.classList.remove('active');
                overlay.setAttribute('aria-hidden', 'true');
            }

            /**
             * Core routine used by both layers.
             * Prevents default navigation, shows overlay, then navigates after
             * 250 ms so the fade-in transition is visible before page unloads.
             */
            function goWithOverlay(e, href, name) {
                var destUrl;
                try { destUrl = new URL(href, window.location.origin); }
                catch (_) { return; }

                /* Skip if already on the destination */
                var destPath = destUrl.pathname.replace(/\/$/, '');
                var currPath = window.location.pathname.replace(/\/$/, '');
                if (destPath === currPath && destUrl.search === window.location.search) return;

                /* Block the native navigation */
                e.preventDefault();
                e.stopImmediatePropagation();   /* stop any other listeners */

                showOverlay(name || pageName(destPath));

                /* Navigate after overlay has faded in */
                setTimeout(function () {
                    window.location.href = destUrl.href;
                }, 250);
            }

            /* ── Layer 1: Directly-bound nav links (most reliable) ─── */
            /* Script is at end-of-body; DOM is fully ready here.       */
            document.querySelectorAll('[data-page-name]').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    goWithOverlay(e, link.getAttribute('href') || '', link.dataset.pageName);
                });
            });

            /* ── Layer 2: Catch-all for other internal links ─────────
               (back buttons, activity-row detail links, etc.)          */
            document.addEventListener('click', function (e) {
                /* Skip if already handled by Layer 1 */
                var link = e.target.closest('[data-page-name]');
                if (link) return;

                link = e.target.closest('a[href]');
                if (!link) return;

                var rawHref = link.getAttribute('href') || '';
                if (!rawHref || rawHref === '#' || rawHref.startsWith('javascript:')) return;
                if (link.target === '_blank') return;

                /* Skip external origins */
                var destUrl;
                try { destUrl = new URL(rawHref, window.location.origin); }
                catch (_) { return; }
                if (destUrl.origin !== window.location.origin) return;

                /* Skip AJAX pagination inside the activities container */
                var ajaxEl = document.getElementById('activities-container');
                if (ajaxEl && ajaxEl.contains(link)) return;

                /* Skip modal openers */
                if (link.hasAttribute('onclick')) return;

                goWithOverlay(e, rawHref, null);
            }, true);

            /* ── Hide on Back/Forward bfcache restore ────────────────── */
            window.addEventListener('pageshow', function () { hideOverlay(); });
        })();
    </script>
</body>
</html>
