@extends('layouts.app')

@section('page_title', 'Rezervācijas')
@section('title', 'Rezervācijas — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in">
    @include('partials._search_sort_bar', [
        'route' => 'reservations.index',
        'search' => $search ?? '',
        'perPage' => $perPage ?? 10,
        'showPerPage' => true,
        'showCreate' => true,
        'createRoute' => 'reservations.create',
        'createLabel' => 'Pievienot rezervāciju',
        'statusOptions' => ['pending' => 'Gaida', 'fulfilled' => 'Izpildītas', 'cancelled' => 'Atceltās'],
        'status' => $status ?? null,
    ])

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grāmata</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Lasītājs</th>
                        @include('partials._sort_header', ['label' => 'Rezervēts', 'field' => 'reserved_at', 'sortField' => $sortField, 'sortDir' => $sortDir, 'route' => 'reservations.index'])
                        <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Statuss</th>
                        <th class="px-5 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Darbības</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reservations as $reservation)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-5 py-4">
                                <a href="{{ route('books.show', $reservation->book) }}" class="text-sm font-medium text-slate-800 hover:text-indigo-600">{{ $reservation->book->title }}</a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white text-xs font-medium">
                                        {{ Str::substr($reservation->reader->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm text-slate-600">{{ $reservation->reader->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">{{ $reservation->reserved_at->format('d.m.Y') }}</td>
                            <td class="px-5 py-4">
                                @if ($reservation->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Gaida
                                    </span>
                                @elseif ($reservation->status === 'fulfilled')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Izpildīta
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Atcelta
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($reservation->status === 'pending')
                                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn inline-flex items-center gap-1.5 bg-gradient-to-r from-slate-500 to-slate-600 text-white px-3 py-2 rounded-lg text-xs font-medium shadow-sm">
                                                Atcelt
                                            </button>
                                        </form>
                                        <form action="{{ route('reservations.fulfill', $reservation) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn inline-flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-3 py-2 rounded-lg text-xs font-medium shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                                Izpildīt
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo rezervāciju?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Dzēst">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/>
                                    </svg>
                                    <p class="text-slate-500 text-sm">
                                        @if ($search)
                                            Nav rezervāciju, kas atbilst meklēšanai "{{ $search }}"
                                        @else
                                            Nav rezervāciju
                                        @endif
                                    </p>
                                    <a href="{{ route('reservations.create') }}" class="btn inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium">Pievienot pirmo rezervāciju</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials._pagination_info', ['items' => $reservations])
</div>
@endsection
