<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestBookIndex extends Command
{
    protected $signature = 'books:test-index {count=10000}';
    protected $description = 'Ģenerē testa grāmatas un testē indeksa veiktspēju';

    private array $titles = [
        // Latviešu grāmatas
        'Cilvēka prāts un tā noslēpumi', 'Sirmā vecā', 'Trīs draugi', 'Ugunszeme', 'Zaļā josta',
        'Vēja laiki', 'Melnā saule', 'Zelta putns', 'Ledus sirds', 'Pēdējā vasara',
        'Klusā osta', 'Svešā pilsēta', 'Baltā grāmata', 'Mēness dārzs', 'Ceļš uz mājām',
        'Sapņu mala', 'Lietus mežs', 'Miglas krasti', 'Saules akmens', 'Tumšā upes krastā',
        'Cerību putni', 'Vecā skola', 'Jaunā pasaule', 'Pazudušais dēls', 'Vientulības balss',
        'Ziemeļu gaisma', 'Rīta rosme', 'Vakara krēsla', 'Pavasara vēji', 'Rudens lapas',
        // Angļu grāmatas
        'The Great Gatsby', 'To Kill a Mockingbird', '1984', 'Pride and Prejudice',
        'The Catcher in the Rye', 'The Lord of the Rings', 'Brave New World',
        'The Hobbit', 'Fahrenheit 451', 'Animal Farm', 'The Chronicles of Narnia',
        'Moby Dick', 'War and Peace', 'The Odyssey', 'Jane Eyre', 'Wuthering Heights',
        'The Picture of Dorian Gray', 'Crime and Punishment', 'The Old Man and the Sea',
        'A Farewell to Arms', 'The Sun Also Rises', 'One Hundred Years of Solitude',
        'Don Quixote', 'The Divine Comedy', 'The Brothers Karamazov',
        // Zinātniskā literatūra
        'Ievads mākslīgā intelekta pamatos', 'Programmēšanas valodu evolūcija',
        'Datu bāzu teorija un prakse', 'Algoritmi: ievads un pielietojums',
        'Introduction to Algorithms', 'Clean Code', 'Design Patterns',
        'The Pragmatic Programmer', 'Structure and Interpretation of Computer Programs',
        'Code Complete', 'Refactoring', 'The Art of Computer Programming',
        // Vēsturiskas grāmatas
        'Latvijas vēsture no sākuma līdz mūsdienām', 'Eiropas civilizācijas attīstība',
        'A History of Western Philosophy', 'The Rise and Fall of the Roman Empire',
        'Otrā pasaules kara hronika', 'The Origins of Political Order',
        'Sapiens: A Brief History of Humankind', 'Homo Deus',
        // Bērnu grāmatas
        'Spritētītis un viņa draugi', 'Zelta zirgs', 'Pūķa ola',
        'The Little Prince', 'Alice in Wonderland', 'Winnie the Pooh',
        'Charlie and the Chocolate Factory', 'Matilda', 'The Jungle Book',
        'Anne of Green Gables', 'The Secret Garden', 'Peter Pan',
        // Dzeja
        'Dzeja un proza', 'Zelta soneti', 'Mūžīgais spārns',
        'The Collected Poems of Robert Frost', 'Leaves of Grass',
        // Filozofija un reliģija
        'Being and Time', 'Thus Spoke Zarathustra', 'The Republic',
        'Meditations', 'The Tao of Programming', 'Zen and the Art of Motorcycle Maintenance',
    ];

    private array $prefixes = [
        'The Complete', 'New', 'Modern', 'Essential', 'Advanced',
        'Fundamentals of', 'Introduction to', 'Principles of', 'Handbook of',
        'The History of', 'The Future of', 'Understanding', 'Exploring',
    ];

    private array $suffixes = [
        'in the Modern World', 'for Beginners', 'and Its Applications',
        'of the 21st Century', 'A Comprehensive Guide', 'From Theory to Practice',
        'Past, Present and Future', 'The Untold Story', 'Revised Edition',
    ];

    public function handle(): void
    {
        $count = (int) $this->argument('count');
        $this->info("Ģenerē $count īstas grāmatas...");

        $existingCount = DB::table('books')->count();
        if ($existingCount > 30) {
            $this->warn("Jau ir $existingCount grāmatas. Vai dzēst testa grāmatas un sākt no jauna?");
            if ($this->confirm('Dzēst?')) {
                $this->info('Dzēš...');
                DB::table('book_category')->whereIn('book_id', function ($q) {
                    $q->select('id')->from('books')->where('isbn', 'like', 'TEST-%');
                })->delete();
                DB::table('author_book')->whereIn('book_id', function ($q) {
                    $q->select('id')->from('books')->where('isbn', 'like', 'TEST-%');
                })->delete();
                DB::table('books')->where('isbn', 'like', 'TEST-%')->delete();
            }
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $authorIds = DB::table('authors')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $branchIds = DB::table('branches')->pluck('id')->toArray();

        if (empty($authorIds) || empty($categoryIds) || empty($branchIds)) {
            $this->error('Trūkst autoru, kategoriju vai filiāļu! Vispirms izpildi db:seed.');
            return;
        }

        $chunk = 500;
        $rows = [];
        $pivotAuthors = [];
        $pivotCategories = [];
        $isbnBase = 'TEST-' . now()->timestamp . '-';

        for ($i = 1; $i <= $count; $i++) {
            $title = $this->generateTitle($i);
            $bookId = null;
            $isbn = $isbnBase . str_pad((string) $i, 6, '0', STR_PAD_LEFT);

            $rows[] = [
                'title' => $title,
                'isbn' => $isbn,
                'branch_id' => $branchIds[array_rand($branchIds)],
                'available_copies' => rand(0, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % $chunk === 0 || $i === $count) {
                foreach ($rows as $row) {
                    $bookId = DB::table('books')->insertGetId($row);
                    $pivotAuthors[] = [
                        'author_id' => $authorIds[array_rand($authorIds)],
                        'book_id' => $bookId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $pivotCategories[] = [
                        'category_id' => $categoryIds[array_rand($categoryIds)],
                        'book_id' => $bookId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('author_book')->insert($pivotAuthors);
                DB::table('book_category')->insert($pivotCategories);

                $rows = [];
                $pivotAuthors = [];
                $pivotCategories = [];
                $bar->advance($i % $chunk === 0 ? $chunk : $i % $chunk);
            }
        }

        $bar->finish();

        $total = DB::table('books')->count();
        $this->newLine();
        $this->info("Kopā grāmatas: $total");

        // Testa meklēšana
        $searchTitle = $this->titles[array_rand($this->titles)];
        $this->newLine();
        $this->info("Meklējam: \"$searchTitle\"");

        $this->line("\n--- EXPLAIN QUERY PLAN (ar indeksu, precīzs match) ---");
        foreach (DB::select('EXPLAIN QUERY PLAN SELECT * FROM books WHERE title = ?', [$searchTitle]) as $row) {
            $this->line('  ' . $row->detail);
        }

        $likeSearch = substr($searchTitle, 0, 6);
        $this->line("\n--- EXPLAIN QUERY PLAN (LIKE \"$likeSearch%\" — daļēji izmanto indeksu) ---");
        foreach (DB::select('EXPLAIN QUERY PLAN SELECT * FROM books WHERE title LIKE ?', ["$likeSearch%"]) as $row) {
            $this->line('  ' . $row->detail);
        }

        $word = explode(' ', $searchTitle)[0] ?? $likeSearch;
        $this->line("\n--- EXPLAIN QUERY PLAN (LIKE \"%$word%\" — pilns skenējums) ---");
        foreach (DB::select('EXPLAIN QUERY PLAN SELECT * FROM books WHERE title LIKE ?', ["%$word%"]) as $row) {
            $this->line('  ' . $row->detail);
        }

        // Ātruma testi
        $this->newLine();
        $this->line('--- Ātruma tests (5 izpildes katrai) ---');

        $tests = [
            "AR indeksu (precīzs match)" => function () use ($searchTitle) {
                DB::select('SELECT * FROM books WHERE title = ?', [$searchTitle]);
            },
            "LIKE ar priedēkli (daļēji izmanto indeksu)" => function () use ($likeSearch) {
                DB::select('SELECT * FROM books WHERE title LIKE ?', ["$likeSearch%"]);
            },
            "LIKE ar jebkuru pozīciju (pilns skenējums)" => function () use ($word) {
                DB::select('SELECT * FROM books WHERE title LIKE ?', ["%$word%"]);
            },
        ];

        foreach ($tests as $label => $fn) {
            $times = [];
            for ($r = 0; $r < 5; $r++) {
                $start = microtime(true);
                $fn();
                $times[] = (microtime(true) - $start) * 1000;
            }
            $avg = array_sum($times) / count($times);
            $this->line("  $label: vid. " . number_format($avg, 3) . ' ms');
        }
    }

    private function generateTitle(int $i): string
    {
        if ($i <= count($this->titles)) {
            return $this->titles[$i - 1];
        }

        $pattern = rand(0, 4);
        return match ($pattern) {
            0 => $this->prefixes[array_rand($this->prefixes)] . ' ' . $this->titles[array_rand($this->titles)],
            1 => $this->titles[array_rand($this->titles)] . ': ' . $this->suffixes[array_rand($this->suffixes)],
            2 => $this->titles[array_rand($this->titles)] . ' — ' . rand(1900, 2026),
            3 => $this->titles[array_rand($this->titles)] . ', ' . $this->titles[array_rand($this->titles)],
            default => $this->prefixes[array_rand($this->prefixes)] . ' ' . $this->titles[array_rand($this->titles)] . ' ' . $this->suffixes[array_rand($this->suffixes)],
        };
    }
}
