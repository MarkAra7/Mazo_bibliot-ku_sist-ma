@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Grāmatas</h1>
        <a href="{{ route('books.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pievienot grāmatu</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nosaukums</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ISBN</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pieejamie eks.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Darbības</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($books as $book)
                    <tr>
                        <td class="px-4 py-3">{{ $book->title }}</td>
                        <td class="px-4 py-3">{{ $book->isbn }}</td>
                        <td class="px-4 py-3">{{ $book->available_copies }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:underline">Skatīt</a>
                            <a href="{{ route('books.edit', $book) }}" class="text-yellow-600 hover:underline">Labot</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirm('Dzēst?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $books->links() }}</div>
@endsection
