<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mazo bibliotēku sistēma')</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4338ca">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Bibliotēka">
    <link rel="apple-touch-icon" href="/images/icon.svg">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <style>
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { transform: translateX(4px); }
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        table tbody tr { transition: background-color 0.15s ease; }
        .badge { transition: all 0.2s ease; }
        .btn { transition: all 0.2s ease; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside class="hidden lg:flex lg:flex-col w-64 bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-950 text-white">
            <div class="flex items-center gap-3 px-6 py-6 border-b border-indigo-700/50">
                <div class="w-10 h-10 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-semibold text-sm tracking-tight">Bibliotēka</h1>
                    <p class="text-xs text-indigo-300/80">Mazo bibliotēku sistēma</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                    </svg>
                    <span>Panelis</span>
                </a>
                <a href="{{ route('books.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('books.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span>Grāmatas</span>
                </a>
                <a href="{{ route('readers.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('readers.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <span>Lasītāji</span>
                </a>
                <a href="{{ route('borrowings.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('borrowings.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                    </svg>
                    <span>Aizņēmumi</span>
                </a>
                <a href="{{ route('reservations.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('reservations.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75M16.5 12v.75M16.5 18v.75M5.25 6h10.5a3 3 0 013 3v9a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9a3 3 0 013-3z"/>
                    </svg>
                    <span>Rezervācijas</span>
                </a>
                <a href="{{ route('fines.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('fines.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Sodi</span>
                </a>
                <div class="pt-4 mt-4 border-t border-indigo-700/30 space-y-1">
                    <p class="px-4 pb-1 text-xs font-semibold text-indigo-300/60 uppercase tracking-wider">Uzziņas</p>
                    <a href="{{ route('authors.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('authors.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span>Autori</span>
                    </a>
                    <a href="{{ route('categories.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
                        </svg>
                        <span>Kategorijas</span>
                    </a>
                    <a href="{{ route('branches.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('branches.*') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>Filiāles</span>
                    </a>
                    <div class="pt-2">
                        <a href="{{ route('statistics') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('statistics') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                            </svg>
                            <span>Statistika</span>
                        </a>
                    </div>
                    <a href="{{ route('system.check') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('system.check') ? 'bg-white/15 text-white shadow-sm' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-2.11 2.11a2.1 2.1 0 01-2.97 0l-.11-.11a2.1 2.1 0 010-2.97l2.11-2.11m3.17 0l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11m-3.17-8.48l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11"/>
                        </svg>
                        <span>Sistēmas pārbaude</span>
                    </a>
                </div>
            </nav>

            <div class="px-4 py-4 border-t border-indigo-700/50">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5">
                    <div class="w-8 h-8 bg-indigo-500/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235.083.487.128.75.128H6m12 0A2.25 2.25 0 0118 6v.878m-12 0A.375.375 0 016.375 6H6m12 0a.375.375 0 00-.375-.375H18M12 6v.878m0 0a.375.375 0 00-.375-.375H12m0 0A.375.375 0 0112.375 6H12m0 0v.878M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-indigo-200/80">v2026.1</span>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-slate-200 shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-8 h-16">
                    <div class="flex items-center gap-3 lg:hidden">
                        <button type="button" id="mobile-menu-btn" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                        </button>
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center lg:hidden">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 lg:flex-none">
                        <h2 class="text-lg font-semibold text-slate-800">@yield('page_title', 'Mazo bibliotēku sistēma')</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:inline text-sm text-slate-500">Labdien!</span>
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-sm font-medium shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="px-4 lg:px-8 py-6">
                    @if (session('success'))
                        <div class="fade-in mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="fade-in mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            </svg>
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleMobileMenu()"></div>
    <div id="mobile-menu" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-indigo-900 via-indigo-800 to-indigo-950 text-white z-50 transform -translate-x-full transition-transform duration-300 lg:hidden">
        <div class="flex items-center justify-between px-6 py-6 border-b border-indigo-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/10 backdrop-blur rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <span class="font-semibold text-sm">Bibliotēka</span>
            </div>
            <button onclick="toggleMobileMenu()" class="p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="px-4 py-6 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                </svg>
                Panelis
            </a>
            <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('books.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                Grāmatas
            </a>
            <a href="{{ route('readers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('readers.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                </svg>
                Lasītāji
            </a>
            <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('borrowings.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                </svg>
                Aizņēmumi
            </a>
            <a href="{{ route('reservations.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('reservations.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75M16.5 12v.75M16.5 18v.75M5.25 6h10.5a3 3 0 013 3v9a3 3 0 01-3 3H5.25a3 3 0 01-3-3V9a3 3 0 013-3z"/>
                </svg>
                Rezervācijas
            </a>
            <a href="{{ route('fines.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('fines.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Sodi
            </a>
            <div class="pt-2 border-t border-indigo-700/30">
                <a href="{{ route('authors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('authors.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Autori
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/></svg>
                    Kategorijas
                </a>
                <a href="{{ route('branches.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('branches.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Filiāles
                </a>
                <a href="{{ route('statistics') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('statistics') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    Statistika
                </a>
                <a href="{{ route('mobile.home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('mobile.*') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                    Mobilā lietotne
                </a>
            </div>
            <a href="{{ route('system.check') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('system.check') ? 'bg-white/15' : 'text-indigo-200 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-2.11 2.11a2.1 2.1 0 01-2.97 0l-.11-.11a2.1 2.1 0 010-2.97l2.11-2.11m3.17 0l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11m-3.17-8.48l2.11-2.11a2.1 2.1 0 012.97 0l.11.11a2.1 2.1 0 010 2.97l-2.11 2.11"/>
                </svg>
                Sistēmas pārbaude
            </a>
        </nav>
    </div>

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobile-menu').classList.toggle('-translate-x-full');
            document.getElementById('mobile-menu-overlay').classList.toggle('hidden');
        }
        document.getElementById('mobile-menu-btn')?.addEventListener('click', toggleMobileMenu);
    </script>
    @stack('scripts')
</body>
</html>