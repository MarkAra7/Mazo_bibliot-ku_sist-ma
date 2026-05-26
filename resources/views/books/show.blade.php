@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
        <div class="space-x-2">
            <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Labot</a>
            <a href="{{ route('books.index') }}" class="text-gray-600 hover:underline">Atpakaļ</a>
        </div>
    </div>

    <div class="bg-white rounded shadow p-6">
        <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
        <p><strong>Pieejamie eksemplāri:</strong> {{ $book->available_copies }}</p>
        <p><strong>Izveidots:</strong> {{ $book->created_at }}</p>
        <p><strong>Atjaunināts:</strong> {{ $book->updated_at }}</p>
    </div>

    <h2 class="text-xl font-bold mt-8 mb-4">Aizņēmumu vēsture</h2>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lasītājs</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aizņemts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Atdots</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($book->borrowings()->with('reader')->get() as $borrowing)
                    <tr>
                        <td class="px-4 py-3">{{ $borrowing->reader->name }}</td>
                        <td class="px-4 py-3">{{ $borrowing->borrowed_at }}</td>
                        <td class="px-4 py-3">{{ $borrowing->returned_at ?? 'Nav atdots' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
