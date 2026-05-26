@extends('layouts.app')

@section('page_title', 'Labot kategoriju')
@section('title', 'Labot kategoriju — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        @include('partials._form_header', ['backRoute' => 'categories.index', 'title' => 'Labot kategoriju'])
        <form action="{{ route('categories.update', $category) }}" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nosaukums</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('name') border-red-300 bg-red-50 @enderror">
                @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Slugs</label>
                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('slug') border-red-300 bg-red-50 @enderror">
                @error('slug') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Skaidrojums</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm @error('description') border-red-300 bg-red-50 @enderror">{{ old('description', $category->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>
            @include('partials._form_actions', ['cancelRoute' => 'categories.index'])
        </form>
    </div>
</div>
@endsection
