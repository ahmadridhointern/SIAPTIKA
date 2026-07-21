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
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3" style="text-decoration: none;">
                    <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                    <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                    <span class="small-caps" style="font-size: 0.65rem;">Panel Admin</span>
                </a>

                {{-- Nav Links + Logout --}}
                <div class="flex items-center gap-5">

                    {{-- Navigation Links --}}
                    <nav class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-1.5 rounded text-xs font-mono font-medium transition-colors duration-150"
                           style="color: {{ request()->routeIs('admin.dashboard') ? '#B8860B' : '#6B6B6B' }}; background: {{ request()->routeIs('admin.dashboard') ? 'rgba(184,134,11,0.08)' : 'transparent' }};">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.activities.index') }}"
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

    {{-- Toast Auto-Dismiss Script --}}
    <script>
        (function () {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(function (toast) {
                setTimeout(function () {
                    toast.classList.add('hiding');
                    setTimeout(function () {
                        toast.remove();
                    }, 320);
                }, 3600);
            });
        })();
    </script>

    {{-- ─── Global Button Loading Utility ──────────────────────────── --}}
    <script>
        /**
         * setButtonLoading(btn, loading)
         *
         * Switches a button to a fixed-size spinner state and back.
         * Stores the original width so the button never changes size.
         *
         * @param {HTMLElement} btn
         * @param {boolean}     loading
         */
        function setButtonLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                // Lock the current pixel dimensions so the button won't shrink
                const rect = btn.getBoundingClientRect();
                btn.style.width  = rect.width  + 'px';
                btn.style.height = rect.height + 'px';
                // Cache original content
                btn.dataset.originalContent = btn.innerHTML;
                btn.dataset.loading = 'true';
                btn.disabled = true;
                // Replace text with centred spinner
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
</body>
</html>
