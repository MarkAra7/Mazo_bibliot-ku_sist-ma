@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Pievienot lasītāju</h1>

    <form action="{{ route('readers.store') }}" method="POST" class="bg-white rounded shadow p-6 max-w-lg">
        @csrf

        <label class="block mb-2 text-sm font-medium">Vārds</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded px-3 py-2 mb-4">

        <label class="block mb-2 text-sm font-medium">E-pasts</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded px-3 py-2 mb-4">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Saglabāt</button>
        <a href="{{ route('readers.index') }}" class="ml-2 text-gray-600 hover:underline">Atcelt</a>
    </form>
@endsection
