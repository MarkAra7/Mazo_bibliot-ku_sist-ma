@extends('layouts.app')

@section('page_title', 'Pievienot aizņēmumu')
@section('title', 'Pievienot aizņēmumu — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-2xl">
    @if (session('tx_info'))
        @php $tx = session('tx_info'); @endphp
        <div class="mb-6 rounded-2xl border shadow-sm @if ($tx['status'] === 'committed') bg-emerald-50 border-emerald-200 @else bg-red-50 border-red-200 @endif overflow-hidden">
            <div class="px-5 py-4 border-b @if ($tx['status'] === 'committed') border-emerald-200 @else border-red-200 @endif flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-lg @if ($tx['status'] === 'committed') bg-emerald-100 text-emerald-600 @else bg-red-100 text-red-600 @endif">
                    @if ($tx['status'] === 'committed') &#10003; @else &#10007; @endif
                </div>
                <div>
                    <p class="font-semibold text-sm @if ($tx['status'] === 'committed') text-emerald-800 @else text-red-800 @endif">
                        Transakcija: {{ $tx['status'] === 'committed' ? 'APSTIPRINĀTA (COMMIT)' : 'ATCELTA (ROLLBACK)' }}
                    </p>
                    <p class="text-xs @if ($tx['status'] === 'committed') text-emerald-600 @else text-red-600 @endif">{{ $tx['time'] }}</p>
                </div>
                @if ($tx['status'] === 'rolled_back')
                    <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Kļūda</span>
                @else
                    <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">Veiksmīgi</span>
                @endif
            </div>
            <div class="px-5 py-3 space-y-1.5 text-sm">
                <div class="grid grid-cols-2 gap-2 pb-2 border-b @if ($tx['status'] === 'committed') border-emerald-100 @else border-red-100 @endif mb-2">
                    <div><span class="text-slate-500">Grāmata:</span> <span class="font-medium">{{ $tx['book'] }}</span></div>
                    <div><span class="text-slate-500">Lasītājs:</span> <span class="font-medium">{{ $tx['reader'] }}</span></div>
                    <div><span class="text-slate-500">Eks. pirms:</span> <span class="font-medium">{{ $tx['copies_before'] }}</span></div>
                    @if (isset($tx['copies_available']))
                        <div><span class="text-slate-500">Eks. transakcijā:</span> <span class="font-medium">{{ $tx['copies_available'] }}</span></div>
                    @endif
                </div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1.5">Izpildes soļi:</p>
                <div class="space-y-1">
                    @foreach ($tx['steps'] as $step)
                        <div class="flex items-start gap-2 text-xs @if (str_starts_with($step, '❌')) text-red-600 @elseif (str_starts_with($step, '✅')) text-emerald-600 @else text-slate-600 @endif">
                            <span class="mt-0.5 shrink-0">
                                @if (str_starts_with($step, '❌')) &#10060;
                                @elseif (str_starts_with($step, '✅')) &#9989;
                                @else &#8226;
                                @endif
                            </span>
                            <span>{{ preg_replace('/^[❌✅]\s*\d+\.\s*/', '', $step) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <a href="{{ route('borrowings.index') }}" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                    </svg>
                </a>
                <h3 class="text-lg font-semibold text-slate-800">Pievienot aizņēmumu</h3>
            </div>
        </div>
        <form action="{{ route('borrowings.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Grāmata</label>
                <select name="book_id" id="book_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('book_id') border-red-300 bg-red-50 @enderror">
                    <option value="" class="text-slate-400">Izvēlies grāmatu</option>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }} {{ $book->available_copies <= 0 ? 'disabled' : '' }} data-copies="{{ $book->available_copies }}">
                            {{ $book->title }} @if ($book->available_copies > 0)
                                ({{ $book->available_copies }} pieejami)
                            @else
                                (nav pieejama)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Lasītājs</label>
                <select name="reader_id" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('reader_id') border-red-300 bg-red-50 @enderror">
                    <option value="" class="text-slate-400">Izvēlies lasītāju</option>
                    @foreach ($readers as $reader)
                        <option value="{{ $reader->id }}" {{ old('reader_id') == $reader->id ? 'selected' : '' }}>
                            {{ $reader->name }} ({{ $reader->email }})
                        </option>
                    @endforeach
                </select>
                @error('reader_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Aizņemšanas datums</label>
                <input type="date" name="borrowed_at" value="{{ old('borrowed_at', date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 @error('borrowed_at') border-red-300 bg-red-50 @enderror">
                @error('borrowed_at')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn inline-flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    Saglabāt
                </button>
                <a href="{{ route('borrowings.index') }}" class="px-6 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition-colors">Atcelt</a>
            </div>
        </form>
    </div>
</div>
@endsection