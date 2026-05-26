<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    private array $authors = [
        ['name' => 'Jānis', 'bio' => 'Latviešu rakstnieks un dzejnieks.'],
        ['name' => 'Anna', 'bio' => 'Mūsdienu prozas autore.'],
        ['name' => 'Pēteris', 'bio' => 'Vēsturnieks un publicists.'],
        ['name' => 'Līga', 'bio' => 'Bērnu grāmatu autore.'],
        ['name' => 'Māris', 'bio' => 'Dzejnieks un tulkotājs.'],
        ['name' => 'Aija', 'bio' => 'Romānu rakstniece.'],
        ['name' => 'Kārlis', 'bio' => 'Zinātniskās fantastikas autors.'],
        ['name' => 'Ilze', 'bio' => 'Detektīvžanra rakstniece.'],
        ['name' => 'Uldis', 'bio' => 'Ceļojumu grāmatu autors.'],
        ['name' => 'Zane', 'bio' => 'Daiļliteratūras tulkotāja un rakstniece.'],
    ];

    public function run(): void
    {
        foreach ($this->authors as $author) {
            Author::firstOrCreate(
                ['name' => $author['name']],
                $author
            );
        }
    }
}
