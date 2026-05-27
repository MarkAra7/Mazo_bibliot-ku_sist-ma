@extends('layouts.app')

@section('page_title', 'Sodi')
@section('title', 'Sodi — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    @php
        $totalUnpaid = \App\Models\Fine::whereNull('paid_at')->sum('amount');
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500 font-medium">Kopā neapmaksāts</p>
                <p class="text-3xl font-bold text-red-600 mt-1">&euro;{{ number_format($totalUnpaid, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    @include('partials._search_sort_bar', [
        'route' => 'fines.index',
        'search' => $search ?? '',
        'perPage' => $perPage ?? 10,
        'showPerPage' => true,
        'showCreate' => false,
        'statusOptions' => ['paid' => 'Apmaksāti', 'unpaid' => 'Neapmaksāti'],
        'status' => $status ?? null,
    ])

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lasītājs</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grāmata</th>
                        @include('partials._sort_header', ['label' => 'Summa', 'field' => 'amount', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'fines.index'])
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Iemesls</th>
                        @include('partials._sort_header', ['label' => 'Apmaksāts', 'field' => 'paid_at', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'fines.index'])
                        <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Darbības</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($fines as $fine)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-4">
                                <a href="{{ route('readers.show', $fine->reader) }}" class="flex items-center gap-2 hover:opacity-80">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-medium">
                                        {{ Str::substr($fine->reader->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $fine->reader->name }}</span>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $fine->borrowing->book->title }}</td>
                            <td class="px-5 py-4 text-sm font-medium text-slate-800">&euro;{{ number_format($fine->amount, 2) }}</td>
                            <td class="px-5 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $fine->reason ?: '—' }}</td>
                            <td class="px-5 py-4">
                                @if ($fine->paid_at)
                                    <span class="text-sm text-slate-500">{{ $fine->paid_at->format('d.m.Y') }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Nav apmaksāts
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$fine->paid_at)
                                        <form action="{{ route('fines.pay', $fine) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-3 py-2 rounded-lg text-xs font-medium shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                                Apmaksāt
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm">
                                        @if ($search)
                                            Nav sodu, kas atbilst meklēšanai "{{ $search }}"
                                        @else
                                            Nav sodu
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials._pagination_info', ['items' => $fines])
</div>
@endsection
