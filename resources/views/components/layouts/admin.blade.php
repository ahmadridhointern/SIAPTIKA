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

    {{-- ─── Global Navigation Function ──────────────────────────── --}}
    {{--
        __goPage(href, name) — dipakai oleh onclick di nav links.
        Menyimpan flag ke sessionStorage agar halaman tujuan tahu
        bahwa overlay "departing" sudah ditangani di sini.
    --}}
    <script>
        function __goPage(href, name) {
            var overlay = document.getElementById('page-transition-overlay');
            var label   = document.getElementById('page-transition-label');

            var dest;
            try { dest = new URL(href, window.location.origin); }
            catch (_) { window.location.href = href; return; }

            var destPath = dest.pathname.replace(/\/$/, '');
            var currPath = window.location.pathname.replace(/\/$/, '');
            if (destPath === currPath && dest.search === window.location.search) return;

            /* Beri sinyal ke halaman tujuan bahwa transisi sudah ditangani */
            try { sessionStorage.setItem('_nav_handled', '1'); } catch (_) {}

            /* Tampilkan overlay pada halaman ini (departing) */
            if (overlay && label) {
                label.textContent = 'Mengarahkan ke Halaman ' + (name || 'Berikutnya');
                overlay.style.transition = 'none';
                overlay.style.opacity    = '1';
                overlay.style.pointerEvents = 'all';
                overlay.setAttribute('aria-hidden', 'false');
                overlay.classList.add('active');
            }

            /* Navigasi setelah overlay terlihat */
            setTimeout(function () {
                window.location.href = dest.href;
            }, 280);
        }
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

                {{-- Brand --}}
                <a href="{{ route('admin.dashboard') }}"
                   onclick="__goPage(this.href,'Dashboard'); return false;"
                   class="flex items-center gap-3" style="text-decoration: none;">
                    <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                    <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                    <span class="small-caps" style="font-size: 0.65rem;">Panel Admin</span>
                </a>

                {{-- Nav Links + Logout --}}
                <div class="flex items-center gap-5">

                    {{-- Navigation Links --}}
                    <nav class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}"
                           onclick="__goPage(this.href,'Dashboard'); return false;"
                           class="px-3 py-1.5 rounded text-xs font-mono font-medium transition-colors duration-150"
                           style="color: {{ request()->routeIs('admin.dashboard') ? '#B8860B' : '#6B6B6B' }}; background: {{ request()->routeIs('admin.dashboard') ? 'rgba(184,134,11,0.08)' : 'transparent' }};">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.activities.index') }}"
                           onclick="__goPage(this.href,'Kegiatan'); return false;"
                           class="px-3 py-1.5 rounded text-xs font-mono font-medium transition-colors duration-150"
                           style="color: {{ request()->routeIs('admin.activities.*') ? '#B8860B' : '#6B6B6B' }}; background: {{ request()->routeIs('admin.activities.*') ? 'rgba(184,134,11,0.08)' : 'transparent' }};">
                            Kegiatan
                        </a>
                    </nav>

                    <span class="h-4 w-px hidden sm:block" style="background-color: #E8E4DF;"></span>

                    {{-- User name --}}
                    <span class="text-xs hidden md:block" style="color: #6B6B6B; font-family: 'Source Sans 3', system-ui, sans-serif;">
                        {{ Auth::user()->name }}
                    </span>

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

    {{-- ─── Page Transition Overlay ──────────────────────────────── --}}
    <div id="page-transition-overlay" aria-hidden="true" aria-live="assertive">
        <div class="page-transition-spinner"></div>
        <p class="page-transition-label" id="page-transition-label">Memuat...</p>
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

    {{-- ─── Entry Reveal Animation (PENDEKATAN BARU) ─────────────── --}}
    {{--
        Strategi: Tampilkan overlay di halaman yang BARU DIMUAT, bukan di
        halaman yang ditinggalkan. Ini 100% reliable karena berjalan saat
        DOM sudah tersedia, tidak bergantung pada event listener apapun.

        Alur:
          1. Halaman dimuat → script ini langsung menampilkan overlay
          2. Jika halaman sebelumnya sudah menangani overlay via __goPage
             (Dashboard → Kegiatan), flag sessionStorage mengindikasikan
             bahwa entry reveal perlu dilewati.
          3. Setelah 500ms → overlay fade-out halus
    --}}
    <script>
        (function () {
            var overlay = document.getElementById('page-transition-overlay');
            var label   = document.getElementById('page-transition-label');
            if (!overlay || !label) return;

            /* Jika halaman lama SUDAH menampilkan overlay via __goPage,
               lewati entry reveal agar tidak double. */
            var handled = false;
            try {
                handled = sessionStorage.getItem('_nav_handled') === '1';
                sessionStorage.removeItem('_nav_handled');
            } catch (_) {}

            if (handled) {
                /* Halaman lama sudah urus overlay. Pastikan overlay bersih. */
                overlay.style.cssText = '';
                overlay.classList.remove('active');
                overlay.setAttribute('aria-hidden', 'true');
                return;
            }

            /* ── Entry Reveal ─────────────────────────────────────────── */
            /* Tentukan nama halaman saat ini dari URL */
            function currentPageName() {
                var p = window.location.pathname.replace(/\/$/, '');
                if (p === '/admin/dashboard')                    return 'Dashboard';
                if (/^\/admin\/activities\/\d+\/edit$/.test(p)) return 'Edit Kegiatan';
                if (/^\/admin\/activities\/\d+$/.test(p))       return 'Detail Kegiatan';
                if (/^\/admin\/activities/.test(p))              return 'Kegiatan';
                return 'Halaman Ini';
            }

            label.textContent = 'Mengarahkan ke Halaman ' + currentPageName();

            /* Tampilkan overlay SEGERA, tanpa transisi (inline style) */
            overlay.style.transition    = 'none';
            overlay.style.opacity       = '1';
            overlay.style.pointerEvents = 'all';
            overlay.setAttribute('aria-hidden', 'false');

            /* Setelah 500ms, fade-out halus lalu bersihkan */
            setTimeout(function () {
                overlay.style.transition = 'opacity 0.35s ease';
                overlay.style.opacity    = '0';

                setTimeout(function () {
                    overlay.style.cssText = '';
                    overlay.classList.remove('active');
                    overlay.setAttribute('aria-hidden', 'true');
                }, 360);
            }, 500);

            /* Batalkan entry reveal jika user menekan Back/Forward */
            window.addEventListener('pageshow', function (e) {
                if (e.persisted) {
                    overlay.style.cssText = '';
                    overlay.classList.remove('active');
                    overlay.setAttribute('aria-hidden', 'true');
                }
            });
        })();
    </script>
</body>
</html>
