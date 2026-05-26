@props([
    'route', 'search' => '', 'perPage' => 10,
    'showPerPage' => true, 'showCreate' => false,
    'createRoute' => null, 'createLabel' => 'Pievienot',
    'statusOptions' => [], 'status' => null,
    'extra' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" action="{{ route($route) }}" class="flex flex-wrap items-center gap-3 w-full">
        <div class="relative flex-1 min-w-[200px] max-w-md">
            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input type="text" name="q" value="{{ $search }}" placeholder="Meklēt..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
        </div>
        @if (count($statusOptions) > 0)
            <select name="status" onchange="this.form.submit()" class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm">
                <option value="" @selected(!$status)>Visi</option>
                @foreach ($statusOptions as $val => $label)
                    <option value="{{ $val }}" @selected($status === $val)>{{ $label }}</option>
                @endforeach
            </select>
        @endif
        @if ($showPerPage)
            <select name="per_page" onchange="this.form.submit()" class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm">
                <option value="10" @selected($perPage == 10)>10</option>
                <option value="25" @selected($perPage == 25)>25</option>
                <option value="50" @selected($perPage == 50)>50</option>
                <option value="100" @selected($perPage == 100)>100</option>
            </select>
        @endif
        @if ($search || $status || $perPage != 10)
            <a href="{{ route($route) }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-colors">Notīrīt</a>
        @endif
        {{ $extra ?? '' }}
        <noscript><button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm">Meklēt</button></noscript>
    </form>
    @if ($showCreate && $createRoute)
        <a href="{{ route($createRoute) }}" class="btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ $createLabel }}
        </a>
    @endif
</div>
