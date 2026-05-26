@extends('layouts.app')

@section('page_title', 'Panelis')
@section('title', 'Panelis — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in space-y-6">
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('books.index') }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Grāmatas</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['books'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ $stats['total_copies'] }} eksemplāri</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('readers.index') }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Lasītāji</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['readers'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('borrowings.index') }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Aizņēmumi</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['borrowings'] }}</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('borrowings.index', ['status' => 'active']) }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Aktīvie</p>
                    <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Atgrieztas</p>
                    <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['returned'] }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-shadow">
            <a href="{{ route('system.check') }}" class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sistēma</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $stats['books'] + $stats['readers'] + $stats['borrowings'] }}</p>
                    <p class="text-xs text-slate-400 mt-1">kopā ieraksti</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-2.11 2.11a2.1 2.1 0 01-2.97 0l-.11-.11a2.1 2.1 0 010-2.97l2.11-2.11m3.17 0l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11m-3.17-8.48l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Pēdējie aizņēmumi</h3>
                <a href="{{ route('borrowings.index') }}" class="text-xs text-indigo-600 hover:underline">Skatīt visus</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentBorrowings as $b)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $b->book->title }}</p>
                            <p class="text-xs text-slate-400">{{ $b->reader->name }} — {{ $b->borrowed_at->format('d.m.Y') }}</p>
                        </div>
                        @if ($b->returned_at)
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Atgriezta</span>
                        @else
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Aktīvs</span>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-400">Nav aizņēmumu</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Populārākās grāmatas</h3>
                <a href="{{ route('books.index') }}" class="text-xs text-indigo-600 hover:underline">Skatīt visas</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($mostBorrowed as $book)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <span class="shrink-0 flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 text-xs font-bold">{{ $loop->iteration }}</span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $book->title }}</p>
                                <p class="text-xs text-slate-400">{{ $book->borrowings_count }} aizņēmumi</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-400">Nav datu</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Aktīvākie lasītāji</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($activeReaders as $reader)
                    <div class="px-6 py-4 flex items-center gap-3 hover:bg-slate-50/50">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ \Str::substr($reader->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-800">{{ $reader->name }}</p>
                            <p class="text-xs text-slate-400">{{ $reader->borrowings_count }} aktīvi aizņēmumi</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-400">Nav aktīvu lasītāju</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Pēdējie žurnāla ieraksti</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($recentLog as $log)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50/50">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $log->book_title }}</p>
                            <p class="text-xs text-slate-400">
                                {{ $log->old_copies }} → {{ $log->new_copies }} eks.
                            </p>
                        </div>
                        <div class="shrink-0 text-xs text-slate-400">{{ $log->changed_at }}</div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-sm text-slate-400">Nav ierakstu</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection