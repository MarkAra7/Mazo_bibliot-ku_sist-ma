<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LibraryService
{
    public function borrowBook(int $bookId, int $readerId, string $borrowedAt): Borrowing
    {
        return DB::transaction(function () use ($bookId, $readerId, $borrowedAt) {
            $book = Book::lockForUpdate()->findOrFail($bookId);

            if ($book->available_copies <= 0) {
                throw new \RuntimeException('Nav pieejamu eksemplāru.');
            }

            return Borrowing::create([
                'book_id' => $bookId,
                'reader_id' => $readerId,
                'borrowed_at' => $borrowedAt,
            ]);
        });
    }

    public function returnBook(int $borrowingId): Borrowing
    {
        return DB::transaction(function () use ($borrowingId) {
            $borrowing = Borrowing::lockForUpdate()->findOrFail($borrowingId);

            if ($borrowing->returned_at !== null) {
                throw new \RuntimeException('Grāmata jau ir atdota.');
            }

            $borrowing->update(['returned_at' => now()->toDateString()]);

            return $borrowing->fresh();
        });
    }

    public function getActiveBorrowings(): Collection
    {
        return Borrowing::with(['book', 'reader'])
            ->whereNull('returned_at')
            ->get();
    }

    public function getReaderHistory(int $readerId): Collection
    {
        return Borrowing::with(['book'])
            ->where('reader_id', $readerId)
            ->orderBy('borrowed_at', 'desc')
            ->get();
    }

    public function getReaderStats(): Collection
    {
        return Reader::select([
            'readers.id',
            'readers.name',
            'readers.email',
            DB::raw('COUNT(CASE WHEN borrowings.returned_at IS NULL THEN 1 END) as active_borrowings'),
            DB::raw('COUNT(borrowings.id) as total_borrowings'),
        ])
            ->leftJoin('borrowings', 'readers.id', '=', 'borrowings.reader_id')
            ->groupBy('readers.id', 'readers.name', 'readers.email')
            ->get();
    }

    public function getMostBorrowedBooks(int $limit = 10): Collection
    {
        return Book::select([
            'books.id',
            'books.title',
            'books.isbn',
            DB::raw('COUNT(borrowings.id) as borrow_count'),
        ])
            ->leftJoin('borrowings', 'books.id', '=', 'borrowings.book_id')
            ->groupBy('books.id', 'books.title', 'books.isbn')
            ->orderBy('borrow_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function calculateReaderFine(int $readerId): array
    {
        $rows = DB::select(
            'SELECT * FROM reader_fines WHERE reader_id = ?',
            [$readerId]
        );

        return [
            'reader_id' => $readerId,
            'items' => $rows,
            'total_fine' => array_sum(array_column($rows, 'fine_amount')),
            'overdue_count' => count($rows),
        ];
    }
}
