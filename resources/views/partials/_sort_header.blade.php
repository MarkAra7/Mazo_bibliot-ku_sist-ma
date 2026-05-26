@props(['label', 'field', 'sortField', 'sortDir', 'route'])

<th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
    <a href="{{ route($route, array_merge(request()->query(), ['sort' => $field, 'dir' => $sortField === $field && $sortDir === 'asc' ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-indigo-600">
        {{ $label }}
        @if ($sortField === $field)
            <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
        @endif
    </a>
</th>
