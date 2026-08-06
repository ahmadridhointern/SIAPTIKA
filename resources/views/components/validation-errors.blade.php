@if($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 overflow-hidden">
        {{-- Header --}}
        <div class="px-5 py-3 border-b border-red-200 flex items-center gap-2.5">
            <svg class="w-4 h-4 text-red-600 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <span class="text-xs font-mono font-semibold tracking-wider uppercase text-red-700">
                Terdapat {{ $errors->count() }} kesalahan pada form
            </span>
        </div>
        {{-- Error List --}}
        <ul class="px-5 py-3 space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-700 flex items-start gap-2">
                    <span class="text-red-400 mt-0.5 flex-shrink-0">•</span>
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
