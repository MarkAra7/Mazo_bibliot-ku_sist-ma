@extends('layouts.app')

@section('page_title', $reader->name)
@section('title', $reader->name . ' — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    <a href="{{ route('readers.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-indigo-600 mb-6">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Atpakaļ uz lasītājiem
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                {{ Str::substr($reader->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">{{ $reader->name }}</h1>
                <p class="text-sm text-slate-500">{{ $reader->email }}</p>
            </div>
            @if ($totalUnpaid > 0)
                <div class="ml-auto flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    <span class="text-sm font-medium text-red-700">{{ number_format($totalUnpaid, 2) }} € neapmaksāti sodi</span>
                </div>
            @else
                <div class="ml-auto flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-sm font-medium text-emerald-700">Nav sodu</span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Aizņēmumi ({{ $reader->borrowings->count() }})</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Grāmata</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Paņemts</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Termiņš</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Atdots</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reader->borrowings as $b)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $b->book->title }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $b->borrowed_at->format('d.m.Y') }}</td>
                                <td class="px-4 py-3 text-sm {{ $b->due_date && !$b->returned_at && now()->gt($b->due_date) ? 'text-red-600 font-medium' : 'text-slate-600' }}">
                                    {{ $b->due_date?->format('d.m.Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $b->returned_at?->format('d.m.Y') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Nav aizņēmumu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Sodi ({{ $reader->fines->count() }})</h2>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Grāmata</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Summa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Statuss</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reader->fines as $fine)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $fine->borrowing?->book?->title ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ number_format($fine->amount, 2) }} €</td>
                                <td class="px-4 py-3">
                                    @if ($fine->paid_at)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Apmaksāts {{ $fine->paid_at instanceof \Carbon\Carbon ? $fine->paid_at->format('d.m.Y') : \Carbon\Carbon::parse($fine->paid_at)->format('d.m.Y') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            Neapmaksāts
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-500">Nav sodu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
