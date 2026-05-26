@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Lasītāji</h1>
        <a href="{{ route('readers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pievienot lasītāju</a>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vārds</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-pasts</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Darbības</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($readers as $reader)
                    <tr>
                        <td class="px-4 py-3">{{ $reader->name }}</td>
                        <td class="px-4 py-3">{{ $reader->email }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <a href="{{ route('readers.edit', $reader) }}" class="text-yellow-600 hover:underline">Labot</a>
                            <form action="{{ route('readers.destroy', $reader) }}" method="POST" class="inline" onsubmit="return confirm('Dzēst?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $readers->links() }}</div>
@endsection
