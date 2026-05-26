@extends('layouts.app')

@section('page_title', 'Labot autoru')
@section('title', 'Labot autoru — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        @include('partials._form_header', ['backRoute' => 'authors.index', 'title' => 'Labot autoru'])
        <form action="{{ route('authors.update', $author) }}" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Vārds</label>
                <input type="text" name="name" value="{{ old('name', $author->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('name') border-red-300 bg-red-50 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Biogrāfija</label>
                <textarea name="bio" rows="4"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('bio') border-red-300 bg-red-50 @enderror">{{ old('bio', $author->bio) }}</textarea>
                @error('bio') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            @include('partials._form_actions', ['cancelRoute' => 'authors.index'])
        </form>
    </div>
</div>
@endsection
