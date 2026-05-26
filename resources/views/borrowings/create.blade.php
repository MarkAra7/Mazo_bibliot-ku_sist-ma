@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Pievienot aizņēmumu</h1>

    <form action="{{ route('borrowings.store') }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf

        <label class="block mb-2 text-sm font-medium">Grāmata</label>
        <select name="book_id" required class="w-full border rounded px-3 py-2 mb-4">
            <option value="">Izvēlies grāmatu</option>
            @foreach ($books as $book)
                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                    {{ $book->title }} ({{ $book->available_copies }} pieejami)
                </option>
            @endforeach
        </select>

        <label class="block mb-2 text-sm font-medium">Lasītājs</label>
        <select name="reader_id" required class="w-full border rounded px-3 py-2 mb-4">
            <option value="">Izvēlies lasītāju</option>
            @foreach ($readers as $reader)
                <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                    {{ $reader->name }} ({{ $reader->email }})
                </option>
            @endforeach
        </select>

        <label class="block mb-2 text-sm font-medium">Aizņemšanas datums</label>
        <input type="date" name="borrowed_at" value="{{ old('borrowed_at', date('Y-m-d')) }}" required class="w-full border rounded px-3 py-2 mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Saglabāt</button>
        <a href="{{ route('borrowings.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
