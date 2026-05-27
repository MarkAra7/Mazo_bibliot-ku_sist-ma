@extends('layouts.app')

@section('page_title', 'Aizņēmumi')
@section('title', 'Aizņēmumi — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    @if (session('tx_info') && session('tx_info')['status'] === 'committed')
        @php $tx = session('tx_info'); @endphp
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-emerald-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold">&#10003;</div>
                <div>
                    <p class="font-semibold text-sm text-emerald-800">Transakcija: APSTIPRINĀTA (COMMIT)</p>
                    <p class="text-xs text-emerald-600">{{ $tx['time'] }}</p>
                </div>
                <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Veiksmīgi</span>
            </div>
            <div class="px-5 py-3 space-y-1.5 text-sm">
                <div class="grid grid-cols-2 gap-2 pb-2 border-b border-emerald-100 mb-2">
                    <div><span class="text-slate-500">Grāmata:</span> <span class="font-medium">{{ $tx['book'] }}</span></div>
                    <div><span class="text-slate-500">Lasītājs:</span> <span class="font-medium">{{ $tx['reader'] }}</span></div>
                    <div><span class="text-slate-500">Eks. pirms:</span> <span class="font-medium">{{ $tx['copies_before'] }}</span></div>
                </div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Izpildes soļi:</p>
                <div class="space-y-1">
                    @foreach ($tx['steps'] as $step)
                        <div class="flex items-start gap-2 text-xs text-slate-600">
                            <span class="mt-0.5 shrink-0">&#8226;</span>
                            <span>{{ preg_replace('/^[✅]\s*\d+\.\s*/', '', $step) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @php
        $total = $borrowings->total();
        $activeCount = $borrowings->whereNull('returned_at')->count();
        $returnedCount = $total - $activeCount;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Kopā aizņēmumi</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $total }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Aktīvie</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $activeCount }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Atgrieztas</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $returnedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('borrowings.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Meklēt pēc grāmatas vai lasītāja..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>
            <select name="status" onchange="this.form.submit()" class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="" @selected(!$status)>Visi</option>
                <option value="active" @selected($status === 'active')>Aktīvie</option>
                <option value="returned" @selected($status === 'returned')>Atgrieztie</option>
            </select>
            <select name="per_page" onchange="this.form.submit()" class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
            </select>
            @if ($search || $status || ($perPage ?? 10) != 10)
                <a href="{{ route('borrowings.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-colors">Notīrīt</a>
            @endif
            <noscript><button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm">Meklēt</button></noscript>
        </form>
        <a href="{{ route('borrowings.create') }}" class="btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Pievienot aizņēmumu
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grāmata</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lasītājs</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('borrowings.index', array_merge(request()->query(), ['sort' => 'borrowed_at', 'dir' => $sortField === 'borrowed_at' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-indigo-600">
                                Aizņemts
                                @if ($sortField === 'borrowed_at')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statuss</th>
                        <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Darbības</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($borrowings as $borrowing)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-4">
                                <a href="{{ route('books.show', $borrowing->book) }}" class="text-sm font-medium text-slate-800 hover:text-indigo-600">{{ $borrowing->book->title }}</a>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('readers.show', $borrowing->reader) }}" class="flex items-center gap-2 hover:opacity-80">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-medium">
                                        {{ Str::substr($borrowing->reader->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $borrowing->reader->name }}</span>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $borrowing->borrowed_at->format('d.m.Y') }}</td>
                            <td class="px-5 py-4">
                                @if ($borrowing->returned_at)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Atgriezta {{ $borrowing->returned_at->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Aktīvs
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$borrowing->returned_at)
                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-3 py-2 rounded-lg text-xs font-medium shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                                Atgriezt
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo aizņēmumu?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Dzēst">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm">
                                        @if ($search)
                                            Nav aizņēmumu, kas atbilst meklēšanai "{{ $search }}"
                                        @else
                                            Nav aizņēmumu
                                        @endif
                                    </p>
                                    <a href="{{ route('borrowings.create') }}" class="btn inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Pievienot pirmo aizņēmumu</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-sm text-slate-500">
            Rāda {{ $borrowings->firstItem() ?? 0 }}–{{ $borrowings->lastItem() ?? 0 }} no {{ $borrowings->total() }} ierakstiem
        </p>
        {{ $borrowings->appends(request()->query())->links() }}
    </div>
</div>
@endsection