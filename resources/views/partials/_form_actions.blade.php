@props(['cancelRoute'])

<div class="flex items-center gap-3 pt-2">
    <button type="submit" class="btn inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
        Saglabāt
    </button>
    <a href="{{ route($cancelRoute) }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Atcelt</a>
</div>
