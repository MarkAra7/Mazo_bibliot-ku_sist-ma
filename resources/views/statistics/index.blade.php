@extends('layouts.app')

@section('page_title', 'Statistika')
@section('title', 'Statistika — Mazo bibliotēku sistēma')

@section('content')
<div class="fade-in" 
     data-monthly='@json($monthlyBorrowings)'
     data-categories='@json($booksByCategory)'
     data-branches='@json($branchStats)'>
    
    <!-- Stats cards row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Grāmatas</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalBooks }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Lasītāji</p>
            <p class="text-3xl font-bold text-slate-800 mt-1">{{ $totalReaders }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Aktīvie aizņēmumi</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">{{ $activeBorrowings }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Neapmaksātie sodi</p>
            <p class="text-3xl font-bold text-red-600 mt-1">€ {{ number_format($unpaidFines, 2) }}</p>
        </div>
    </div>
    
    <!-- Second row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Kopā aizņēmumi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalBorrowings }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Vid. aizņēmumi/lasītājs</p>
            <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $avgBorrowingsPerReader }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Kopā soda naudas</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">€ {{ number_format($totalFines, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
            <p class="text-sm text-slate-500 font-medium">Gaidošās rezervācijas</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingReservations }}</p>
        </div>
    </div>
    
    <!-- Charts row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Aizņēmumi pa mēnešiem</h3>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Grāmatas pa kategorijām</h3>
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>
    
    <!-- Tables row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Populārākās grāmatas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Grāmata</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aizņēmumi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($mostBorrowedBooks as $i => $book)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3 text-sm text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-slate-800">{{ $book->title }}</td>
                                <td class="px-5 py-3 text-sm text-right font-semibold text-indigo-600">{{ $book->borrowings_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Aktīvākie lasītāji</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Lasītājs</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aizņēmumi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($mostActiveReaders as $i => $reader)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3 text-sm text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-slate-800">{{ $reader->name }}</td>
                                <td class="px-5 py-3 text-sm text-right font-semibold text-indigo-600">{{ $reader->borrowings_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('[data-monthly]');
    if (!container) return;
    
    const monthlyData = JSON.parse(container.dataset.monthly);
    const categoryData = JSON.parse(container.dataset.categories);
    const branchData = JSON.parse(container.dataset.branches);
    
    if (document.getElementById('monthlyChart')) {
        new Chart(document.getElementById('monthlyChart'), {
            type: 'bar',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: 'Aizņēmumi',
                    data: monthlyData.map(d => d.total),
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }
    
    if (document.getElementById('categoryChart')) {
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: categoryData.map(d => d.name),
                datasets: [{
                    data: categoryData.map(d => d.total),
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)', 'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)', 'rgba(239, 68, 68, 0.8)',
                        'rgba(139, 92, 246, 0.8)', 'rgba(14, 165, 233, 0.8)',
                        'rgba(236, 72, 153, 0.8)', 'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)', 'rgba(168, 85, 247, 0.8)',
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { boxWidth: 12, padding: 12 } } }
            }
        });
    }
});
</script>
@endpush
