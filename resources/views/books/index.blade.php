@extends('layouts.app')

@section('page_title', 'Grāmatas')
@section('title', 'Grāmatas — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    @php
        $total = $books->total();
        $available = \App\Models\Book::sum('available_copies');
        $borrowed = \App\Models\Borrowing::whereNull('returned_at')->count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Kopā grāmatas</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $total }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Pieejami eksemplāri</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $available }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Aizņemtas</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $borrowed }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form method="GET" action="{{ route('books.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Meklēt pēc nosaukuma vai ISBN..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
            </div>
            <select name="per_page" onchange="this.form.submit()" class="px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500">
                <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
            </select>
            @if ($search || ($perPage ?? 10) != 10)
                <a href="{{ route('books.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-slate-100 transition-colors">Notīrīt</a>
            @endif
            <noscript><button type="submit" class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm">Meklēt</button></noscript>
        </form>
        <a href="{{ route('books.create') }}" class="btn inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium shadow-sm shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Pievienot grāmatu
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('books.index', array_merge(request()->query(), ['sort' => 'title', 'dir' => $sortField === 'title' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-indigo-600">
                                Nosaukums
                                @if ($sortField === 'title')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Autori</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategorijas</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ISBN</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('books.index', array_merge(request()->query(), ['sort' => 'available_copies', 'dir' => $sortField === 'available_copies' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}" class="inline-flex items-center gap-1 hover:text-indigo-600">
                                Pieejamie eks.
                                @if ($sortField === 'available_copies')
                                    <svg class="w-3 h-3 {{ $sortDir === 'asc' ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
                                @endif
                            </a>
                        </th>
                        <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Darbības</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($books as $book)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-4">
                                <a href="{{ route('books.show', $book) }}" class="text-sm font-medium text-slate-800 hover:text-indigo-600">{{ $book->title }}</a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-sm text-slate-600 truncate max-w-[160px]">
                                    @if ($book->authors->count())
                                        {{ $book->authors->pluck('name')->join(', ') }}
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="text-sm text-slate-600 truncate max-w-[160px]">
                                    @if ($book->categories->count())
                                        {{ $book->categories->pluck('name')->join(', ') }}
                                    @else
                                        <span class="text-slate-300">—</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <code class="text-sm text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $book->isbn }}</code>
                            </td>
                            <td class="px-5 py-4">
                                @if ($book->available_copies > 3)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $book->available_copies }} pieejami
                                    </span>
                                @elseif ($book->available_copies > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ $book->available_copies }} pieejami
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Nav pieejama
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('books.show', $book) }}" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Skatīt">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('books.edit', $book) }}" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Labot">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo grāmatu?')">
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
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm">
                                        @if ($search)
                                            Nav grāmatu, kas atbilst meklēšanai "{{ $search }}"
                                        @else
                                            Nav pievienotu grāmatu
                                        @endif
                                    </p>
                                    <a href="{{ route('books.create') }}" class="btn inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Pievienot pirmo grāmatu</a>
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
            Rāda {{ $books->firstItem() ?? 0 }}–{{ $books->lastItem() ?? 0 }} no {{ $books->total() }} ierakstiem
        </p>
        {{ $books->appends(request()->query())->links() }}
    </div>
</div>
@endsection