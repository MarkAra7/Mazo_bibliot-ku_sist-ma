@extends('layouts.app')

@section('page_title', $book->title)
@section('title', $book->title . ' — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('books.index') }}" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-800">{{ $book->title }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('books.edit', $book) }}" class="btn inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                </svg>
                Labot
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Informācija</h2>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase">ISBN</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-800">
                            <code class="bg-slate-100 px-2 py-1 rounded-md">{{ $book->isbn }}</code>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase">Pieejamie eksemplāri</dt>
                        <dd class="mt-1">
                            @if ($book->available_copies > 3)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $book->available_copies }} pieejami
                                </span>
                            @elseif ($book->available_copies > 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    {{ $book->available_copies }} pieejami
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    Nav pieejama
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase">Izveidots</dt>
                        <dd class="mt-1 text-sm text-slate-600">{{ $book->created_at->format('d.m.Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase">Atjaunināts</dt>
                        <dd class="mt-1 text-sm text-slate-600">{{ $book->updated_at->format('d.m.Y H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Aizņēmumu vēsture</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lasītājs</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aizņemts</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Atdots</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statuss</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($book->borrowings()->with('reader')->latest('borrowed_at')->get() as $borrowing)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ $borrowing->reader->name }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-600">{{ $borrowing->borrowed_at->format('d.m.Y') }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-600">{{ $borrowing->returned_at ? $borrowing->returned_at->format('d.m.Y') : '—' }}</td>
                                    <td class="px-5 py-4">
                                        @if ($borrowing->returned_at)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Atgriezta
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Aktīvs
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                            </svg>
                                            <p class="text-slate-500 text-sm">Nav aizņēmumu vēstures</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection