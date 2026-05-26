<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Reader;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalReaders = Reader::count();
        $activeBorrowings = Borrowing::whereNull('returned_at')->count();
        $totalBorrowings = Borrowing::count();
        
        $totalFines = Fine::sum('amount');
        $unpaidFines = Fine::whereNull('paid_at')->sum('amount');
        
        $avgBorrowingsPerReader = $totalReaders > 0 
            ? round($totalBorrowings / $totalReaders, 1) 
            : 0;
        
        $mostBorrowedBooks = Book::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->take(10)
            ->get();
        
        $mostActiveReaders = Reader::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->take(10)
            ->get();
        
        $monthlyBorrowings = Borrowing::select(
            DB::raw("strftime('%Y-%m', borrowed_at) as month"),
            DB::raw('count(*) as total')
        )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        $booksByCategory = DB::table('category_book')
            ->join('categories', 'category_book.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->get();
        
        $branchStats = Book::select('branch_id')
            ->with('branch')
            ->selectRaw('count(*) as total')
            ->groupBy('branch_id')
            ->get();
        
        $pendingReservations = Reservation::where('status', 'pending')->count();
        
        return view('statistics.index', compact(
            'totalBooks', 'totalReaders', 'activeBorrowings', 'totalBorrowings',
            'totalFines', 'unpaidFines', 'avgBorrowingsPerReader',
            'mostBorrowedBooks', 'mostActiveReaders',
            'monthlyBorrowings', 'booksByCategory', 'branchStats',
            'pendingReservations'
        ));
    }
}
