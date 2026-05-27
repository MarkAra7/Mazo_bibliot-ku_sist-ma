@extends('layouts.app')

@section('page_title', $author->name)
@section('title', $author->name . ' — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    <a href="{{ route('authors.index') }}" class="inline-flex items-center gap-1.5 text-sm text-dark-400 hover:text-indigo-400 mb-6">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
        Atpakaļ uz autoriem
    </a>

    <div class="bg-surface rounded-2xl shadow-sm border border-dark-700/50 p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xl font-bold">
                {{ Str::substr($author->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-dark-100">{{ $author->name }}</h1>
                <p class="text-sm text-dark-400 mt-1">{{ $author->bio ?: 'Nav biogrāfijas' }}</p>
            </div>
            <div class="ml-auto flex items-center gap-2 px-4 py-2 bg-indigo-500/10 border border-indigo-400/20 rounded-xl">
                <span class="text-sm font-medium text-indigo-300">{{ $author->books->count() }} grāmatas</span>
            </div>
        </div>
    </div>

    <h2 class="text-lg font-semibold text-dark-100 mb-4">Grāmatas ({{ $author->books->count() }})</h2>
    <div class="bg-surface rounded-2xl shadow-sm border border-dark-700/50 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Nosaukums</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">ISBN</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase">Pieejamie eks.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-dark-700/30">
                @forelse ($author->books as $book)
                    <tr class="hover:bg-dark-800/30">
                        <td class="px-5 py-4">
                            <a href="{{ route('books.show', $book) }}" class="text-sm font-medium text-dark-100 hover:text-indigo-400">{{ $book->title }}</a>
                        </td>
                        <td class="px-5 py-4 text-sm text-dark-400">{{ $book->isbn }}</td>
                        <td class="px-5 py-4 text-sm text-dark-300">{{ $book->available_copies }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-12 text-center text-sm text-dark-400">Nav grāmatu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
