@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Aizņēmumi</h1>
        <a href="{{ route('borrowings.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pievienot aizņēmumu</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grāmata</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lasītājs</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aizņemts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Atdots</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Darbības</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($borrowings as $borrowing)
                    <tr>
                        <td class="px-4 py-3">{{ $borrowing->book->title }}</td>
                        <td class="px-4 py-3">{{ $borrowing->reader->name }}</td>
                        <td class="px-4 py-3">{{ $borrowing->borrowed_at }}</td>
                        <td class="px-4 py-3">{{ $borrowing->returned_at ?? '—' }}</td>
                        <td class="px-4 py-3 space-x-2">
                            @if (!$borrowing->returned_at)
                                <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:underline">Atgriezt</button>
                                </form>
                            @endif
                            <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" class="inline" onsubmit="return confirm('Dzēst?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $borrowings->links() }}</div>
@endsection
