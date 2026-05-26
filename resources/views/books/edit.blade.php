@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Labot grāmatu</h1>

    <form action="{{ route('books.update', $book) }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf @method('PUT')

        <label class="block mb-2 text-sm font-medium">Nosaukums</label>
        <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full border rounded px-3 py-2 mb-4">

        <label class="block mb-2 text-sm font-medium">ISBN</label>
        <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" required class="w-full border rounded px-3 py-2 mb-4">

        <label class="block mb-2 text-sm font-medium">Pieejamie eksemplāri</label>
        <input type="number" name="available_copies" value="{{ old('available_copies', $book->available_copies) }}" min="0" required class="w-full border rounded px-3 py-2 mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Saglabāt</button>
        <a href="{{ route('books.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
