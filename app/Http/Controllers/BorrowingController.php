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
    public function index(Request $request): View
    {
        $query = Borrowing::with(['book', 'reader']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('book', fn($b) => $b->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('reader', fn($r) => $r->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($status === 'returned') {
                $query->whereNotNull('returned_at');
            }
        }

        $sortField = $request->get('sort', 'borrowed_at');
        $sortDir = $request->get('dir', 'desc');
        $allowed = ['borrowed_at', 'returned_at'];
        if (in_array($sortField, $allowed)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->get('per_page', 10), 100);

        return view('borrowings.index', [
            'borrowings' => $query->paginate($perPage)->withQueryString(),
            'sortField' => $sortField,
            'sortDir' => $sortDir,
            'search' => $search,
            'perPage' => $perPage,
            'status' => $request->get('status'),
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
