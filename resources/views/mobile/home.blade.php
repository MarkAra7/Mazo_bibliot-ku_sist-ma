@extends('layouts.app')

@section('page_title', 'Mobilā lietotne')
@section('title', 'Mobilā lietotne — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-lg mx-auto">
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white mb-6 shadow-lg">
        <p class="text-indigo-200 text-sm font-medium">Mazo bibliotēku sistēma</p>
        <h2 class="text-2xl font-bold mt-1">Mobilā lietotne</h2>
        <p class="text-indigo-200 text-sm mt-2">Ātra piekļuve visām funkcijām</p>
    </div>
    
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('books.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Grāmatas</span>
        </a>
        <a href="{{ route('readers.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Lasītāji</span>
        </a>
        <a href="{{ route('borrowings.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Aizņēmumi</span>
        </a>
        <a href="{{ route('reservations.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75M16.5 12v.75M16.5 18v.75M5.25 6h10.5a3 3 0 013 3v9a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9a3 3 0 013-3z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Rezervācijas</span>
        </a>
        <a href="{{ route('fines.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Sodi</span>
        </a>
        <a href="{{ route('authors.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 hover:shadow-md transition-all active:scale-95">
            <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Autori</span>
        </a>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mt-4">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">API piekļuve</h3>
        <p class="text-sm text-slate-600 mb-3">Izmanto REST API, lai integrētu sistēmu ar citām lietotnēm.</p>
        <div class="space-y-2">
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/books</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/readers</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/borrowings</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/stats</code>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mt-4">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Eksportēt datus</h3>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('export.csv', 'books') }}" class="text-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 hover:bg-slate-100">Grāmatas CSV</a>
            <a href="{{ route('export.csv', 'readers') }}" class="text-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 hover:bg-slate-100">Lasītāji CSV</a>
            <a href="{{ route('export.csv', 'borrowings') }}" class="text-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 hover:bg-slate-100">Aizņēmumi CSV</a>
            <a href="{{ route('export.csv', 'fines') }}" class="text-center px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-sm text-slate-700 hover:bg-slate-100">Sodi CSV</a>
        </div>
    </div>
</div>
@endsection
