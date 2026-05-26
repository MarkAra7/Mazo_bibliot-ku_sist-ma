@props(['backRoute', 'title'])

<div class="px-6 py-5 border-b border-slate-100">
    <div class="flex items-center gap-3">
        <a href="{{ route($backRoute) }}" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <h3 class="text-lg font-semibold text-slate-800">{{ $title }}</h3>
    </div>
</div>
