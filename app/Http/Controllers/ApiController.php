<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Reader;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    // Books
    public function books(): JsonResponse
    {
        return response()->json(Book::with(['authors', 'categories', 'branch'])->get());
    }
    
    public function bookShow(Book $book): JsonResponse
    {
        $book->load(['authors', 'categories', 'branch', 'borrowings.reader']);
        return response()->json($book);
    }
    
    public function bookStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'isbn' => 'required|max:20|unique:books',
            'available_copies' => 'required|integer|min:0',
        ]);
        
        $book = Book::create($validated);
        return response()->json($book, 201);
    }
    
    // Readers
    public function readers(): JsonResponse
    {
        return response()->json(Reader::all());
    }
    
    public function readerShow(Reader $reader): JsonResponse
    {
        $reader->load('borrowings.book');
        return response()->json($reader);
    }
    
    // Borrowings
    public function borrowings(): JsonResponse
    {
        return response()->json(Borrowing::with(['book', 'reader'])->get());
    }
    
    public function activeBorrowings(): JsonResponse
    {
        return response()->json(Borrowing::with(['book', 'reader'])->whereNull('returned_at')->get());
    }
    
    public function borrowingStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'reader_id' => 'required|exists:readers,id',
            'borrowed_at' => 'required|date',
        ]);
        
        try {
            DB::transaction(function () use ($validated, &$borrowing) {
                $book = Book::lockForUpdate()->findOrFail($validated['book_id']);
                if ($book->available_copies <= 0) {
                    throw new \RuntimeException('Nav pieejamu eksemplāru.');
                }
                $borrowing = Borrowing::create($validated);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
        
        return response()->json($borrowing->load(['book', 'reader']), 201);
    }
    
    public function borrowingReturn(Borrowing $borrowing): JsonResponse
    {
        if ($borrowing->returned_at) {
            return response()->json(['error' => 'Grāmata jau atgriezta'], 422);
        }
        $borrowing->update(['returned_at' => now()->toDateString()]);
        return response()->json($borrowing->load(['book', 'reader']));
    }
    
    // Fines
    public function fines(): JsonResponse
    {
        return response()->json(Fine::with(['borrowing.book', 'reader'])->get());
    }
    
    // Reservations
    public function reservations(): JsonResponse
    {
        return response()->json(Reservation::with(['book', 'reader'])->get());
    }
    
    // Statistics
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_books' => Book::count(),
            'total_readers' => Reader::count(),
            'active_borrowings' => Borrowing::whereNull('returned_at')->count(),
            'total_borrowings' => Borrowing::count(),
            'total_fines' => Fine::sum('amount'),
            'unpaid_fines' => Fine::whereNull('paid_at')->sum('amount'),
        ]);
    }
}
