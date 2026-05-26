<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthorSeeder::class,
            CategorySeeder::class,
            BranchSeeder::class,
            BookSeeder::class,
            ReaderSeeder::class,
            BorrowingSeeder::class,
            ReservationSeeder::class,
            FineSeeder::class,
        ]);
    }
}
