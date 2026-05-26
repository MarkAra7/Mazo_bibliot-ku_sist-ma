<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SystemCheckController extends Controller
{
    public function index(): View
    {
        $triggers = DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'trigger' ORDER BY name");
        $views = DB::select("SELECT name, sql FROM sqlite_master WHERE type = 'view' ORDER BY name");
        $recentLog = DB::table('book_log')
            ->join('books', 'book_log.book_id', '=', 'books.id')
            ->select('book_log.*', 'books.title as book_title')
            ->latest('changed_at')
            ->limit(20)
            ->get();
        $activeBorrowings = DB::table('active_borrowings')->get();
        $stats = [
            'books' => DB::table('books')->count(),
            'readers' => DB::table('readers')->count(),
            'borrowings' => DB::table('borrowings')->count(),
            'total_copies' => DB::table('books')->sum('available_copies'),
            'active_borrowings' => DB::table('borrowings')->whereNull('returned_at')->count(),
            'log_entries' => DB::table('book_log')->count(),
        ];

        return view('system-check', compact('triggers', 'views', 'recentLog', 'activeBorrowings', 'stats'));
    }
}
