<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Reader;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        if (Borrowing::count() > 0) {
            return;
        }

        $books = Book::all();
        $readers = Reader::all();

        $borrowings = [
            ['book' => 0, 'reader' => 0, 'borrowed_at' => '2026-05-01', 'returned_at' => '2026-05-15'],
            ['book' => 1, 'reader' => 1, 'borrowed_at' => '2026-05-05', 'returned_at' => null],
            ['book' => 2, 'reader' => 2, 'borrowed_at' => '2026-05-10', 'returned_at' => null],
            ['book' => 3, 'reader' => 0, 'borrowed_at' => '2026-05-12', 'returned_at' => null],
            ['book' => 0, 'reader' => 3, 'borrowed_at' => '2026-05-15', 'returned_at' => null],
            ['book' => 4, 'reader' => 4, 'borrowed_at' => '2026-05-17', 'returned_at' => null],
            ['book' => 5, 'reader' => 5, 'borrowed_at' => '2026-05-18', 'returned_at' => null],
            ['book' => 6, 'reader' => 6, 'borrowed_at' => '2026-05-20', 'returned_at' => null],
            ['book' => 7, 'reader' => 7, 'borrowed_at' => '2026-05-20', 'returned_at' => null],
            ['book' => 8, 'reader' => 0, 'borrowed_at' => '2026-05-21', 'returned_at' => null],
            ['book' => 10, 'reader' => 8, 'borrowed_at' => '2026-05-22', 'returned_at' => null],
            ['book' => 11, 'reader' => 9, 'borrowed_at' => '2026-05-22', 'returned_at' => null],
            ['book' => 12, 'reader' => 10, 'borrowed_at' => '2026-05-23', 'returned_at' => null],
            ['book' => 13, 'reader' => 11, 'borrowed_at' => '2026-05-23', 'returned_at' => null],
            ['book' => 14, 'reader' => 12, 'borrowed_at' => '2026-05-24', 'returned_at' => null],
            ['book' => 15, 'reader' => 13, 'borrowed_at' => '2026-05-24', 'returned_at' => null],
            ['book' => 16, 'reader' => 14, 'borrowed_at' => '2026-05-24', 'returned_at' => null],
            ['book' => 17, 'reader' => 15, 'borrowed_at' => '2026-05-25', 'returned_at' => null],
            ['book' => 18, 'reader' => 16, 'borrowed_at' => '2026-05-25', 'returned_at' => null],
            ['book' => 19, 'reader' => 17, 'borrowed_at' => '2026-05-25', 'returned_at' => null],
            ['book' => 10, 'reader' => 18, 'borrowed_at' => '2026-05-10', 'returned_at' => '2026-05-20'],
            ['book' => 11, 'reader' => 19, 'borrowed_at' => '2026-05-12', 'returned_at' => '2026-05-22'],
        ];

        foreach ($borrowings as $b) {
            Borrowing::create([
                'book_id' => $books[$b['book']]->id,
                'reader_id' => $readers[$b['reader']]->id,
                'borrowed_at' => $b['borrowed_at'],
                'returned_at' => $b['returned_at'],
            ]);
        }
    }
}
