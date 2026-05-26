@extends('layouts.app')

@section('page_title', 'Labot filiāli')
@section('title', 'Labot filiāli — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        @include('partials._form_header', ['backRoute' => 'branches.index', 'title' => 'Labot filiāli'])
        <form action="{{ route('branches.update', $branch) }}" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nosaukums</label>
                <input type="text" name="name" value="{{ old('name', $branch->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('name') border-red-300 bg-red-50 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Adrese</label>
                <textarea name="address" rows="3" required maxlength="500"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('address') border-red-300 bg-red-50 @enderror">{{ old('address', $branch->address) }}</textarea>
                @error('address') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Tālrunis</label>
                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('phone') border-red-300 bg-red-50 @enderror">
                @error('phone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            @include('partials._form_actions', ['cancelRoute' => 'branches.index'])
        </form>
    </div>
</div>
@endsection
