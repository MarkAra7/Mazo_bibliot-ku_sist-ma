@extends('layouts.app')

@section('page_title', 'Pievienot aizņēmumu')
@section('title', 'Pievienot aizņēmumu — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                </a>
                <h3 class="text-lg font-semibold text-slate-800">Pievienot aizņēmumu</h3>
            </div>
        </div>
        <form action="{{ route('borrowings.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Grāmata</label>
                <select name="book_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('book_id') border-red-300 bg-red-50 @enderror">
                    <option value="" class="text-slate-400">Izvēlies grāmatu</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }} {{ $book->available_copies <= 0 ? 'disabled' : '' }}>
                            {{ $book->title }} @if ($book->available_copies > 0)
                                ({{ $book->available_copies }} pieejami)
                            @else
                                (nav pieejama)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Lasītājs</label>
                <select name="reader_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('reader_id') border-red-300 bg-red-50 @enderror">
                    <option value="" class="text-slate-400">Izvēlies lasītāju</option>
                    @foreach ($readers as $reader)
                        <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                            {{ $reader->name }} ({{ $reader->email }})
                        </option>
                    @endforeach
                </select>
                @error('reader_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Aizņemšanas datums</label>
                <input type="date" name="borrowed_at" value="{{ old('borrowed_at', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('borrowed_at') border-red-300 bg-red-50 @enderror">
                @error('borrowed_at')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Saglabāt
                </button>
                <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Atcelt</a>
            </div>
        </form>
    </div>
</div>
@endsection