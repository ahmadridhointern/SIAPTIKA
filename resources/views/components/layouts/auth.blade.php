<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIAPTIKA — Sistem Informasi Administrasi Kegiatan Bidang APTIKA, Dinas Kominfotik Provinsi Riau">
    <title>{{ $title ?? 'Login' }} — SIAPTIKA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes btnSpin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-16" style="background-color: #FAFAF8;">

    {{-- Ambient glow background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full"
             style="background: radial-gradient(circle, rgba(184,134,11,0.04) 0%, transparent 70%);"></div>
    </div>

    <div class="w-full max-w-md relative z-10">

        {{-- Logo / Wordmark --}}
        <div class="text-center mb-10">
            <div class="mb-3">
                <span class="small-caps" style="color: #B8860B;">Dinas Kominfotik Provinsi Riau</span>
            </div>
            <h1 class="font-serif text-4xl" style="color: #1A1A1A; letter-spacing: -0.01em; line-height: 1.2;">
                SIAPTIKA
            </h1>
            <hr class="rule-line mt-4 mb-2 mx-auto" style="max-width: 80px;">
            <p class="text-sm" style="color: #6B6B6B; font-family: 'Source Sans 3', system-ui, sans-serif;">
                Sistem Informasi Administrasi Kegiatan Bidang APTIKA
            </p>
        </div>

        {{-- Main slot --}}
        {{ $slot }}

    </div>

</body>
</html>
