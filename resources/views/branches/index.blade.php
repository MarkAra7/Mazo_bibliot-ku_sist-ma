@extends('layouts.app')

@section('page_title', 'Filiāles')
@section('title', 'Filiāles — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    @include('partials._search_sort_bar', [
        'route' => 'branches.index',
        'search' => $search ?? '',
        'perPage' => $perPage ?? 10,
        'showPerPage' => true,
        'showCreate' => true,
        'createRoute' => 'branches.create',
        'createLabel' => 'Pievienot filiāli',
        'statusOptions' => [],
    ])

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        @include('partials._sort_header', ['label' => 'Nosaukums', 'field' => 'name', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'branches.index'])
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Adrese</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tālrunis</th>
                        @include('partials._sort_header', ['label' => 'Grāmatas', 'field' => 'books_count', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'branches.index'])
                        @include('partials._sort_header', ['label' => 'Pievienots', 'field' => 'created_at', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'branches.index'])
                        <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Darbības</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($branches as $branch)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-4 text-sm font-medium text-slate-800">{{ $branch->name }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $branch->address ?: '—' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $branch->phone ?: '—' }}</td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $branch->books_count }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500">{{ $branch->created_at->format('d.m.Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('branches.edit', $branch) }}" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Labot">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo filiāli?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Dzēst">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-slate-500 text-sm">Nav filiāļu</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials._pagination_info', ['items' => $branches])
</div>
@endsection
