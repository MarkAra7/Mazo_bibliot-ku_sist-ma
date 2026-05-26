<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'books' => Book::count(),
            'total_copies' => Book::sum('available_copies'),
            'readers' => Reader::count(),
            'borrowings' => Borrowing::count(),
            'active' => Borrowing::whereNull('returned_at')->count(),
            'returned' => Borrowing::whereNotNull('returned_at')->count(),
        ];

        $recentBorrowings = Borrowing::with(['book', 'reader'])
            ->latest('borrowed_at')
            ->limit(5)
            ->get();

        $mostBorrowed = Book::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->limit(5)
            ->get();

        $activeReaders = Reader::withCount(['borrowings' => fn($q) => $q->whereNull('returned_at')])
            ->orderBy('borrowings_count', 'desc')
            ->limit(5)
            ->get();

        $recentLog = DB::table('book_log')
            ->join('books', 'book_log.book_id', '=', 'books.id')
            ->select('book_log.*', 'books.title as book_title')
            ->latest('changed_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'stats', 'recentBorrowings', 'mostBorrowed', 'activeReaders', 'recentLog'
        ));
    }
}
