<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $authors = Author::all();
        $categories = Category::all();
        $branches = Branch::all();

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
            ['title' => 'Ceļš uz laimi', 'isbn' => '9789984380011', 'available_copies' => 4],
            ['title' => 'Zelta zirgs', 'isbn' => '9789984380012', 'available_copies' => 3],
            ['title' => 'Pūt, vējiņi!', 'isbn' => '9789984380013', 'available_copies' => 2],
            ['title' => 'Dullais Dauka', 'isbn' => '9789984380014', 'available_copies' => 5],
            ['title' => 'Mērnieku laiki', 'isbn' => '9789984380015', 'available_copies' => 1],
            ['title' => 'Zaļā grāmata', 'isbn' => '9789984380016', 'available_copies' => 3],
            ['title' => 'Purva bridējs', 'isbn' => '9789984380017', 'available_copies' => 4],
            ['title' => 'Kopotas rakstus', 'isbn' => '9789984380018', 'available_copies' => 2],
            ['title' => 'Saules spēle', 'isbn' => '9789984380019', 'available_copies' => 6],
            ['title' => 'Rīgas sargi', 'isbn' => '9789984380020', 'available_copies' => 1],
            ['title' => 'Laika vārti', 'isbn' => '9789984380021', 'available_copies' => 3],
            ['title' => 'Ziemeļu mežs', 'isbn' => '9789984380022', 'available_copies' => 2],
            ['title' => 'Jūras vilki', 'isbn' => '9789984380023', 'available_copies' => 5],
            ['title' => 'Pērkona dūrdi', 'isbn' => '9789984380024', 'available_copies' => 4],
            ['title' => 'Rudens lapas', 'isbn' => '9789984380025', 'available_copies' => 2],
            ['title' => 'Pavasara atmoda', 'isbn' => '9789984380026', 'available_copies' => 3],
            ['title' => 'Lietus lāses', 'isbn' => '9789984380027', 'available_copies' => 1],
            ['title' => 'Vēja grāmata', 'isbn' => '9789984380028', 'available_copies' => 4],
            ['title' => 'Sniega karaliene', 'isbn' => '9789984380029', 'available_copies' => 2],
            ['title' => 'Noslēpumu sala', 'isbn' => '9789984380030', 'available_copies' => 3],
        ];

        foreach ($books as $i => $data) {
            $book = Book::firstOrCreate(
                ['isbn' => $data['isbn']],
                ['title' => $data['title'], 'available_copies' => $data['available_copies']]
            );

            $book->branch()->associate($branches->random())->save();
            $book->authors()->sync([$authors[$i % $authors->count()]->id]);
            $book->categories()->sync([$categories[$i % $categories->count()]->id]);
        }
    }
}
