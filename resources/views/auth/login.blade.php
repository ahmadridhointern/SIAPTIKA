<x-layouts.auth title="Login Admin">

    {{-- Card --}}
    <div class="card-serif card-serif-accent p-8 md:p-10">

        {{-- Card Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-5">
                <span class="h-px flex-1" style="background-color: #E8E4DF;"></span>
                <span class="small-caps">Portal Admin</span>
                <span class="h-px flex-1" style="background-color: #E8E4DF;"></span>
            </div>
            <h2 class="font-serif text-2xl" style="color: #1A1A1A; letter-spacing: -0.01em;">
                Masuk ke Sistem
            </h2>
            <p class="mt-1 text-sm" style="color: #6B6B6B;">
                Masukkan kredensial akun Administrator Anda.
            </p>
        </div>

        {{-- Success Message (dari logout) --}}
        @if (session('success'))
            <div class="mb-5 alert-error" style="background-color: #f0fdf4; border-color: #bbf7d0; border-left-color: #22c55e; color: #15803d;">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message (dari redirect middleware) --}}
        @if (session('error'))
            <div class="mb-5 alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Login --}}
        <form id="form-login" method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf

            {{-- Email --}}
            <div class="mb-5">
                <label for="email"
                       class="block mb-1.5 text-sm font-medium"
                       style="font-family: 'Source Sans 3', system-ui, sans-serif; color: #1A1A1A;">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    placeholder="admin@siaptika.id"
                    class="input-serif @error('email') border-red-400 @enderror"
                    required
                >
                @error('email')
                    <p class="mt-1.5 text-xs" style="color: #ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-7">
                <label for="password"
                       class="block mb-1.5 text-sm font-medium"
                       style="font-family: 'Source Sans 3', system-ui, sans-serif; color: #1A1A1A;">
                    Password
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="input-serif @error('password') border-red-400 @enderror"
                    required
                >
                @error('password')
                    <p class="mt-1.5 text-xs" style="color: #ef4444;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button id="btn-login" type="submit" class="btn-primary w-full">
                Masuk
            </button>

        </form>

    </div>

    <p class="text-center mt-6 text-xs" style="color: #6B6B6B; font-family: 'IBM Plex Mono', monospace; letter-spacing: 0.05em;">
        Akses khusus untuk Administrator sistem.
    </p>

    <script>
        (function () {
            /* Lock login form visually on submit — NEVER disable inputs (disabled fields are NOT submitted by browser) */
            var form = document.getElementById('form-login');
            var btn  = document.getElementById('btn-login');
            if (form && btn) {
                form.addEventListener('submit', function () {
                    try { sessionStorage.setItem('siaptika_show_splash_admin', 'true'); } catch (_) {}

                    var rect = btn.getBoundingClientRect();
                    btn.style.width  = rect.width  + 'px';
                    btn.style.height = rect.height + 'px';
                    btn.style.pointerEvents = 'none';
                    btn.dataset.loading = 'true';
                    btn.innerHTML = '<span style="display:inline-flex;align-items:center;justify-content:center;width:1.2rem;height:1.2rem;border:2.5px solid #FFFFFF;border-top-color:transparent;border-radius:50%;animation:btnSpin 0.65s linear infinite;margin:0 auto;"></span>';

                    var card = form.closest('.card-serif');
                    if (card) {
                        card.style.position = 'relative';
                        var overlay = document.createElement('div');
                        overlay.id = 'login-lock-overlay';
                        overlay.style.cssText = 'position:absolute;inset:0;background:rgba(255,255,255,0.45);border-radius:inherit;z-index:10;cursor:not-allowed;pointer-events:all;';
                        card.appendChild(overlay);
                    }

                    var timer = setTimeout(function() {
                        btn.style.pointerEvents = '';
                        btn.dataset.loading = 'false';
                        btn.style.width = '';
                        btn.style.height = '';
                        btn.innerHTML = 'Masuk';
                        var ol = document.getElementById('login-lock-overlay');
                        if (ol) ol.remove();

                        var errAlert = document.createElement('div');
                        errAlert.className = 'mb-5 alert-error';
                        errAlert.textContent = 'Waktu permintaan habis (timeout). Silakan periksa koneksi internet Anda dan coba lagi.';
                        form.parentNode.insertBefore(errAlert, form);
                    }, 20000);

                    window.addEventListener('pagehide', function() { clearTimeout(timer); }, { once: true });
                });
            }
        })();
    </script>


</x-layouts.auth>

