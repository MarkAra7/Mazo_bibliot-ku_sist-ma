@extends('layouts.app')

@section('page_title', 'Mobilā lietotne')
@section('title', 'Mobilā lietotne — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in max-w-lg mx-auto">
    <div id="install-banner" class="hidden mb-4 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-2xl p-4 shadow-lg flex items-center gap-4">
        <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-white">Instalēt lietotni</p>
            <p class="text-xs text-indigo-200">Pievieno sākuma ekrānam ātrai piekļuvei</p>
        </div>
        <button id="install-btn" class="px-4 py-2 bg-white text-indigo-700 rounded-xl text-sm font-semibold shadow-sm hover:bg-indigo-50 transition-colors">
            Instalēt
        </button>
        <button id="install-dismiss" class="p-1 rounded-lg hover:bg-white/10 text-indigo-200 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white mb-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/15 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <div>
                    <p class="text-indigo-200 text-xs font-medium">Mazo bibliotēku sistēma</p>
                    <h2 class="text-lg font-bold">Mobilā lietotne</h2>
                </div>
            </div>
            <span id="online-status" class="px-2 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-200 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Tiešsaistē
            </span>
        </div>
        <p class="text-indigo-200 text-sm">Ātra piekļuve visām bibliotēkas funkcijām</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('borrowings.create') }}" class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 flex flex-col items-center gap-3 text-white shadow-sm active:scale-95 transition-transform">
            <svg class="w-8 h-8 text-amber-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            <span class="text-sm font-semibold">Jauns aizņēmums</span>
        </a>
        <a href="{{ route('books.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Grāmatas</span>
        </a>
        <a href="{{ route('readers.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Lasītāji</span>
        </a>
        <a href="{{ route('borrowings.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Aizņēmumi</span>
        </a>
        <a href="{{ route('reservations.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75M16.5 12v.75M16.5 18v.75M5.25 6h10.5a3 3 0 013 3v9a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9a3 3 0 013-3z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Rezervācijas</span>
        </a>
        <a href="{{ route('fines.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Sodi</span>
        </a>
        <a href="{{ route('authors.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-sky-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Autori</span>
        </a>
        <a href="{{ route('branches.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center gap-3 shadow-sm active:scale-95 transition-transform">
            <div class="w-14 h-14 bg-teal-100 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
            </div>
            <span class="text-sm font-semibold text-slate-800">Filiāles</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 mt-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Eksportēt datus</h3>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('export.csv', 'books') }}" class="text-center px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-100 active:bg-slate-200 transition-colors">Grāmatas CSV</a>
            <a href="{{ route('export.csv', 'readers') }}" class="text-center px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-100 active:bg-slate-200 transition-colors">Lasītāji CSV</a>
            <a href="{{ route('export.csv', 'borrowings') }}" class="text-center px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-100 active:bg-slate-200 transition-colors">Aizņēmumi CSV</a>
            <a href="{{ route('export.csv', 'fines') }}" class="text-center px-3 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-medium text-slate-700 hover:bg-slate-100 active:bg-slate-200 transition-colors">Sodi CSV</a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-5 mt-4 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">API piekļuve</h3>
        <p class="text-sm text-slate-600 mb-3">Izmanto REST API, lai integrētu sistēmu.</p>
        <div class="space-y-2">
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/books</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/readers</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/borrowings</code>
            <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-600 font-mono">GET /api/stats</code>
        </div>
    </div>

    <p class="text-center text-xs text-slate-400 mt-6 mb-4">
        Mazo bibliotēku sistēma &copy; {{ date('Y') }}
    </p>
</div>
@endsection

@push('scripts')
<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js')
        .then(() => console.log('SW reģistrēts'))
        .catch(() => console.log('SW reģistrācija neizdevās'));
}

window.addEventListener('online', () => {
    const el = document.getElementById('online-status');
    if (el) {
        el.className = 'px-2 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-200 flex items-center gap-1.5';
        el.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Tiešsaist\u0113';
    }
});
window.addEventListener('offline', () => {
    const el = document.getElementById('online-status');
    if (el) {
        el.className = 'px-2 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-200 flex items-center gap-1.5';
        el.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Bezsaist\u0113';
    }
});

let installPrompt = null;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    installPrompt = e;
    const banner = document.getElementById('install-banner');
    if (banner && !localStorage.getItem('pwa-install-dismissed')) {
        banner.classList.remove('hidden');
    }
});

document.getElementById('install-btn')?.addEventListener('click', async () => {
    if (!installPrompt) return;
    installPrompt.prompt();
    const result = await installPrompt.userChoice;
    if (result.outcome === 'accepted') {
        document.getElementById('install-banner').classList.add('hidden');
    }
    installPrompt = null;
});

document.getElementById('install-dismiss')?.addEventListener('click', () => {
    document.getElementById('install-banner').classList.add('hidden');
    localStorage.setItem('pwa-install-dismissed', '1');
});
</script>
@endpush
