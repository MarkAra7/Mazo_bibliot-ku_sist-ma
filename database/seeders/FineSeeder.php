<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
    public function run(): void
    {
        $returnedBorrowings = Borrowing::whereNotNull('returned_at')->get();

        foreach ($returnedBorrowings->take(5) as $borrowing) {
            Fine::firstOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    'reader_id' => $borrowing->reader_id,
                    'amount' => rand(100, 500) / 100,
                    'reason' => 'Novēlota grāmatas atgriešana',
                    'paid_at' => rand(0, 1) ? now()->subDays(rand(1, 20)) : null,
                ]
            );
        }
    }
}
