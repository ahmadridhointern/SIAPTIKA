@props(['status'])

@if($status === 'Selesai')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-200']) }}
          role="status" aria-label="Status: Selesai">
        <span class="w-1.5 h-1.5 rounded-full bg-gray-400" aria-hidden="true"></span>Selesai
    </span>
@elseif($status === 'Sudah Berlangsung')
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-200']) }}
          role="status" aria-label="Status: Sudah Berlangsung">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse" aria-hidden="true"></span>Sudah Berlangsung
    </span>
@else
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-mono font-semibold uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-200']) }}
          role="status" aria-label="Status: Direncana">
        <span class="w-1.5 h-1.5 rounded-full bg-blue-500" aria-hidden="true"></span>Direncana
    </span>
@endif
