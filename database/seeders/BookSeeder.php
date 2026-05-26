<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['title' => 'Cilvēka prāts', 'isbn' => '9789984380001', 'available_copies' => 3],
            ['title' => 'Trīs draugi', 'isbn' => '9789984380002', 'available_copies' => 2],
            ['title' => 'Sirmā vecā', 'isbn' => '9789984380003', 'available_copies' => 5],
            ['title' => 'Ugunszeme', 'isbn' => '9789984380004', 'available_copies' => 1],
            ['title' => 'Lakstīgalas dziesma', 'isbn' => '9789984380005', 'available_copies' => 4],
            ['title' => 'Es nemiršu nekad', 'isbn' => '9789984380006', 'available_copies' => 2],
            ['title' => 'Vējiem līdzi', 'isbn' => '9789984380007', 'available_copies' => 3],
            ['title' => 'Mazais princis', 'isbn' => '9789984380008', 'available_copies' => 6],
            ['title' => 'Alķīmiķis', 'isbn' => '9789984380009', 'available_copies' => 2],
            ['title' => 'Pēdējā templieša mantojums', 'isbn' => '9789984380010', 'available_copies' => 1],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
