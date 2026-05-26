@extends('layouts.app')

@section('page_title', 'Pievienot rezervāciju')
@section('title', 'Pievienot rezervāciju — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        @include('partials._form_header', ['backRoute' => 'reservations.index', 'title' => 'Pievienot rezervāciju'])
        <form action="{{ route('reservations.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Grāmata</label>
                <select name="book_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('book_id') border-red-300 bg-red-50 @enderror">
                    <option value="" class="text-slate-400">Izvēlies grāmatu</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} ({{ $book->available_copies }} pieejami)
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
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Rezervēšanas datums</label>
                <input type="date" name="reserved_at" value="{{ old('reserved_at', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('reserved_at') border-red-300 bg-red-50 @enderror">
                @error('reserved_at')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            @include('partials._form_actions', ['cancelRoute' => 'reservations.index'])
        </form>
    </div>
</div>
@endsection
