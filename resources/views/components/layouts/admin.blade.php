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

    {{-- Navigation Bar --}}
    <header style="background-color: #FFFFFF; border-bottom: 1px solid #E8E4DF;">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <span class="font-serif text-xl" style="color: #1A1A1A; letter-spacing: -0.01em;">SIAPTIKA</span>
                <span class="h-4 w-px" style="background-color: #E8E4DF;"></span>
                <span class="small-caps" style="font-size: 0.65rem;">Panel Admin</span>
            </div>

            {{-- Admin Info + Logout --}}
            <div class="flex items-center gap-5">
                <span class="text-sm" style="color: #6B6B6B; font-family: 'Source Sans 3', system-ui, sans-serif;">
                    {{ Auth::guard('admin')->user()->name }}
                </span>
                <form id="form-logout" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button id="btn-logout"
                            type="submit"
                            class="text-sm font-medium transition-colors duration-200"
                            style="font-family: 'Source Sans 3', system-ui, sans-serif; color: #6B6B6B; background: none; border: none; cursor: pointer; padding: 0;"
                            onmouseover="this.style.color='#B8860B'"
                            onmouseout="this.style.color='#6B6B6B'">
                        Keluar
                    </button>
                </form>
            </div>

        </nav>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-6 py-10">
        {{ $slot }}
    </main>

</body>
</html>
