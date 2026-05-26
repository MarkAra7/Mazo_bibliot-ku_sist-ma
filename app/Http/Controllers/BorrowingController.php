<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(): View
    {
        return view('borrowings.index', [
            'borrowings' => Borrowing::with(['book', 'reader'])->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('borrowings.create', [
            'books' => Book::all(),
            'readers' => Reader::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'borrowed_at' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

                if ($book->available_copies <= 0) {
                    throw new \RuntimeException('Nav pieejamu eksemplāru.');
                }

                Borrowing::create([
                    'book_id' => $validated['book_id'],
                    'reader_id' => $validated['reader_id'],
                    'borrowed_at' => $validated['borrowed_at'],
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['book_id' => $e->getMessage()])->withInput();
        }

        return redirect()->route('borrowings.index')->with('success', 'Aizņēmums reģistrēts!');
    }

    public function return(Borrowing $borrowing): RedirectResponse
    {
        try {
            DB::transaction(function () use ($borrowing) {
                if ($borrowing->returned_at !== null) {
                    throw new \RuntimeException('Šī grāmata jau ir atdota.');
                }

                $borrowing->update(['returned_at' => now()->toDateString()]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('borrowings.index')->with('success', 'Grāmata atdota!');
    }

    public function destroy(Borrowing $borrowing): RedirectResponse
    {
        DB::transaction(function () use ($borrowing) {
            if ($borrowing->returned_at === null) {
                $borrowing->book->increment('available_copies');
            }

            $borrowing->delete();
        });

        return redirect()->route('borrowings.index')->with('success', 'Aizņēmums dzēsts!');
    }
}
