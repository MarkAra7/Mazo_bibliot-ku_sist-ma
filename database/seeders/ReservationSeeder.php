<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Reader;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $books = Book::where('available_copies', '<=', 0)->get();
        $readers = Reader::all();

        if ($books->isEmpty() || $readers->isEmpty()) {
            return;
        }

        foreach ($books->take(5) as $i => $book) {
            Reservation::firstOrCreate(
                ['book_id' => $book->id, 'reader_id' => $readers[$i % $readers->count()]->id],
                ['reserved_at' => now()->subDays(rand(1, 10))->toDateString()]
            );
        }
    }
}
