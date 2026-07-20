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

    {{-- Footer note --}}
    <p class="text-center mt-6 text-xs" style="color: #6B6B6B; font-family: 'IBM Plex Mono', monospace; letter-spacing: 0.05em;">
        Hanya Administrator yang dapat mengakses sistem ini.
    </p>

</x-layouts.auth>
