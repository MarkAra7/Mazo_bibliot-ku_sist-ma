@extends('layouts.app')

@section('page_title', 'Sistēmas pārbaude')
@section('title', 'Sistēmas pārbaude — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in space-y-6">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['books'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Grāmatas</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['readers'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Lasītāji</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['borrowings'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Aizņēmumi</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['total_copies'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Eksemplāri</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $stats['active_borrowings'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Aktīvi aizņ.</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 text-center">
            <p class="text-2xl font-bold text-indigo-600">{{ $stats['log_entries'] }}</p>
            <p class="text-xs text-slate-500 mt-1">Žurnāla ieraksti</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Trigeri</h3>
            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ count($triggers) }} aktīvi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nosaukums</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">SQL</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statuss</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($triggers as $trigger)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-3 text-sm font-mono text-slate-800">{{ $trigger->name }}</td>
                            <td class="px-5 py-3 text-xs font-mono text-slate-500 max-w-xl truncate">{{ $trigger->sql }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktīvs
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500 text-sm">Nav trigeru</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Skatījumi (Views)</h3>
            <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ count($views) }} aktīvi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nosaukums</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Definīcija</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($views as $view)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-3 text-sm font-mono text-slate-800">{{ $view->name }}</td>
                            <td class="px-5 py-3 text-xs font-mono text-slate-500 max-w-xl truncate">{{ $view->sql }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-5 py-8 text-center text-slate-500 text-sm">Nav skatījumu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Žurnāls (book_log)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Grāmata</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Vecā vērt.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jaunā vērt.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Laiks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentLog as $log)
                            <tr class="hover:bg-slate-50/50 text-sm">
                                <td class="px-4 py-3 text-slate-800">{{ $log->book_title }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $log->old_copies }}</td>
                                <td class="px-4 py-3 font-medium {{ $log->new_copies > $log->old_copies ? 'text-emerald-600' : 'text-amber-600' }}">{{ $log->new_copies }}</td>
                                <td class="px-4 py-3 text-slate-400 text-xs">{{ $log->changed_at }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Nav ierakstu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Aktīvie aizņēmumi (skats)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Grāmata</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Lasītājs</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Aizņemts</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($activeBorrowings as $ab)
                            <tr class="hover:bg-slate-50/50 text-sm">
                                <td class="px-4 py-3 text-slate-800">{{ $ab->book_title }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $ab->reader_name }}</td>
                                <td class="px-4 py-3 text-slate-500">{{ $ab->borrowed_at }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Nav aktīvu aizņēmumu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection