<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateDatabase extends Command
{
    protected $signature = 'db:populate
        {--books=10000 : Grāmatu skaits}
        {--authors=5000 : Autor skaits}
        {--readers=2000 : Lasītāju skaits}
        {--borrowings=50000 : Aizņēmumu skaits}
        {--reservations=3000 : Rezervāciju skaits}
        {--force : Bez apstiprinājuma}';

    protected $description = 'Ģenerē lielu datu apjomu testēšanai';

    private array $lvFirstNames = [
        'Jānis', 'Anna', 'Pēteris', 'Līga', 'Māris', 'Aija', 'Kārlis', 'Ilze', 'Uldis', 'Zane',
        'Andris', 'Baiba', 'Artūrs', 'Dace', 'Kaspars', 'Elīna', 'Miks', 'Sanita', 'Rihards', 'Laura',
        'Aivars', 'Inese', 'Gints', 'Antra', 'Edgars', 'Ieva', 'Valdis', 'Kristīne', 'Normunds', 'Liene',
        'Gatis', 'Agnese', 'Lauris', 'Vineta', 'Armands', 'Laila', 'Modris', 'Elita', 'Dainis', 'Iveta',
        'Reinis', 'Marta', 'Ivars', 'Rasma', 'Toms', 'Ludmila', 'Jorens', 'Mirdza', 'Aldis', 'Brigita',
        'Oskars', 'Arta', 'Gundars', 'Inga', 'Viesturs', 'Daina', 'Harijs', 'Sarmīte', 'Aleksis', 'Dārta',
        'Uģis', 'Māra', 'Vilnis', 'Silvija', 'Rolands', 'Vija', 'Gvido', 'Rita', 'Vladimirs', 'Olga',
        'Alvis', 'Judīte', 'Imants', 'Benita', 'Viktors', 'Helēna', 'Leonards', 'Irēna', 'Ēriks', 'Tamāra',
        'Sandis', 'Līvija', 'Mārtiņš', 'Regīna', 'Arnis', 'Ausma', 'Gatis', 'Sanita', 'Rūdolfs', 'Vizma',
        'Artis', 'Velga', 'Egils', 'Ārija', 'Namejs', 'Maruta', 'Zigurds', 'Biruta', 'Alberts', 'Valerija',
    ];

    private array $lvLastNames = [
        'Bērziņš', 'Kalniņš', 'Ozols', 'Liepiņš', 'Krūmiņš', 'Vītols', 'Pētersons', 'Aboliņš', 'Cielava',
        'Dzirkalis', 'Ezeriņš', 'Grinbergs', 'Jansons', 'Kļaviņš', 'Lācis', 'Muižnieks', 'Niedra',
        'Paegle', 'Rozītis', 'Sproģis', 'Tūbelis', 'Upītis', 'Vējš', 'Zariņš', 'Aumeisters', 'Briedis',
        'Celmiņš', 'Dzenis', 'Freimanis', 'Gailis', 'Irbe', 'Jēkabsons', 'Kauliņš', 'Lapiņš', 'Mednis',
        'Nariņš', 'Osis', 'Puriņš', 'Riekstiņš', 'Saulītis', 'Tauriņš', 'Vāgners', 'Zālītis', 'Ābols',
        'Bisenieks', 'Damberga', 'Eglītis', 'Feldmanis', 'Grīnvalds', 'Holsteins', 'Jaunzems', 'Ķikulis',
        'Legzdiņš', 'Mazvērsīts', 'Naglis', 'Ogriņš', 'Plaudis', 'Rācenis', 'Siliņš', 'Treimanis',
        'Ūdris', 'Vaivads', 'Znotiņš', 'Avots', 'Blumbergs', 'Cālītis', 'Druviņš', 'Endziņš', 'Goba',
    ];

    private array $bookTitles = [
        'Cilvēka prāts un tā noslēpumi', 'Sirmā vecā', 'Trīs draugi', 'Ugunszeme', 'Zaļā josta',
        'Vēja laiki', 'Melnā saule', 'Zelta putns', 'Ledus sirds', 'Pēdējā vasara',
        'Klusā osta', 'Svešā pilsēta', 'Baltā grāmata', 'Mēness dārzs', 'Ceļš uz mājām',
        'Sapņu mala', 'Lietus mežs', 'Miglas krasti', 'Saules akmens', 'Tumšā upes krastā',
        'The Great Gatsby', 'To Kill a Mockingbird', '1984', 'Pride and Prejudice',
        'The Catcher in the Rye', 'Lord of the Rings', 'Brave New World', 'The Hobbit',
        'Fahrenheit 451', 'Animal Farm', 'Moby Dick', 'War and Peace', 'The Odyssey',
        'Sapiens', 'Homo Deus', 'Clean Code', 'Design Patterns', 'Introduction to Algorithms',
        'Latvijas vēsture', 'Eiropas civilizācija', 'Sprīdītis', 'Zelta zirgs', 'Pūķa ola',
        'Little Prince', 'Alice in Wonderland', 'Charlie and the Chocolate Factory',
        'Dzeja un proza', 'Mūžīgais spārns', 'Meditations', 'The Republic',
    ];

    private array $prefixes = ['The Complete', 'New', 'Essential', 'Advanced', 'Fundamentals of',
        'Introduction to', 'Principles of', 'Handbook of', 'The History of', 'Modern'];

    private array $suffixes = ['in the Modern World', 'for Beginners', 'A Comprehensive Guide',
        'Past Present and Future', 'Revised Edition', 'From Theory to Practice'];

    public function handle(): void
    {
        $bookCount = (int) $this->option('books');
        $authorCount = (int) $this->option('authors');
        $readerCount = (int) $this->option('readers');
        $borrowingCount = (int) $this->option('borrowings');
        $reservationCount = (int) $this->option('reservations');

        if (!$this->option('force')) {
            $this->warn("Tiks ģenerēts liels datu apjoms!");
            $this->line("  Autori: $authorCount");
            $this->line("  Grāmatas: $bookCount");
            $this->line("  Lasītāji: $readerCount");
            $this->line("  Aizņēmumi: $borrowingCount");
            $this->line("  Rezervācijas: $reservationCount");
            if (!$this->confirm('Turpināt?')) return;
        }

        $this->seedLookupTables();
        $this->generateAuthors($authorCount);
        $this->generateBooks($bookCount);
        $this->generateReaders($readerCount);
        $this->generateBorrowings($borrowingCount);
        $this->generateReservations($reservationCount);
        $this->generateFines();

        $this->newLine();
        $this->info('=== KOPSAVILKUMS ===');
        $this->line('Autori:     ' . DB::table('authors')->count());
        $this->line('Grāmatas:   ' . DB::table('books')->count());
        $this->line('Lasītāji:   ' . DB::table('readers')->count());
        $this->line('Aizņēmumi:  ' . DB::table('borrowings')->count());
        $this->line('Rezervācijas: ' . DB::table('reservations')->count());
        $this->line('Sodi:       ' . DB::table('fines')->count());
        $this->line('Autoru piesaistes: ' . DB::table('author_book')->count());
        $this->line('Kategoriju piesaistes: ' . DB::table('book_category')->count());
    }

    private function seedLookupTables(): void
    {
        $branches = DB::table('branches')->count();
        if ($branches === 0) {
            $this->info('Sežu filiāles...');
            DB::table('branches')->insert([
                ['name' => 'Centra filiāle', 'address' => 'Brīvības iela 1, Rīga', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Teikas filiāle', 'address' => 'Lielvārdes iela 24, Rīga', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Ķengaraga filiāle', 'address' => 'Maskavas iela 210, Rīga', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Zolitūdes filiāle', 'address' => 'Rostokas iela 41, Rīga', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Vecrīgas filiāle', 'address' => 'Kaļķu iela 16, Rīga', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        $categories = DB::table('categories')->count();
        if ($categories === 0) {
            $this->info('Sežu kategorijas...');
            DB::table('categories')->insert([
                ['name' => 'Daiļliteratūra', 'slug' => 'daililiteratura', 'description' => 'Romāni, stāsti, dzeja', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Zinātniskā literatūra', 'slug' => 'zinatniska-literatura', 'description' => 'Fizika, ķīmija, bioloģija', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Vēsture', 'slug' => 'vesture', 'description' => 'Vēstures grāmatas', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Programmēšana', 'slug' => 'programmesana', 'description' => 'IT un programmēšana', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Bērnu literatūra', 'slug' => 'bernu-literatura', 'description' => 'Bērnu grāmatas', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Filozofija', 'slug' => 'filozofija', 'description' => 'Filozofija un psiholoģija', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Māksla', 'slug' => 'maksla', 'description' => 'Māksla un dizains', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Valodas', 'slug' => 'valodas', 'description' => 'Valodu mācības', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    private function generateAuthors(int $count): void
    {
        $existing = DB::table('authors')->count();
        if ($existing >= $count) {
            $this->info("Autori: jau $existing");
            return;
        }
        $needed = $count - $existing;
        $this->info("Ģenerē $needed autoru...");

        $bar = $this->output->createProgressBar($needed);
        $bar->start();

        $chunk = 500;
        $rows = [];
        for ($i = 1; $i <= $needed; $i++) {
            $rows[] = [
                'name' => $this->lvFirstNames[array_rand($this->lvFirstNames)] . ' '
                    . $this->lvLastNames[array_rand($this->lvLastNames)],
                'bio' => rand(0, 3) > 0 ? 'Latviešu rakstnieks un tulkotājs.' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($i % $chunk === 0 || $i === $needed) {
                DB::table('authors')->insert($rows);
                $rows = [];
                $bar->advance(min($chunk, $i % $chunk === 0 ? $chunk : $i));
            }
        }
        $bar->finish();
        $this->newLine();
    }

    private function generateBooks(int $count): void
    {
        $existingTest = DB::table('books')->where('isbn', 'like', 'TEST-%')->count();
        if ($existingTest >= $count) {
            $this->info("Grāmatas: jau $existingTest testa grāmatu");
            return;
        }
        $needed = $count - $existingTest;
        $this->info("Ģenerē $needed grāmatas...");

        $authorIds = DB::table('authors')->pluck('id')->toArray();
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $branchIds = DB::table('branches')->pluck('id')->toArray();

        $bar = $this->output->createProgressBar($needed);
        $bar->start();

        $chunk = 500;
        $lastId = DB::table('books')->where('isbn', 'like', 'TEST-%')->max('id') ?? 0;
        $isbnBase = 'TESTB' . now()->format('ymd') . '-';

        $bookRows = [];
        $pivotAuthors = [];
        $pivotCategories = [];

        for ($i = 1; $i <= $needed; $i++) {
            $title = $this->generateTitle($i);

            $bookRows[] = [
                'title' => $title,
                'isbn' => $isbnBase . str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                'branch_id' => $branchIds[array_rand($branchIds)],
                'available_copies' => $this->weightedAvailableCopies(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % $chunk === 0 || $i === $needed) {
                foreach ($bookRows as $row) {
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
                $bookRows = [];
                $pivotAuthors = [];
                $pivotCategories = [];
                $bar->advance($i % $chunk === 0 ? $chunk : $i % $chunk);
            }
        }
        $bar->finish();
        $this->newLine();
    }

    private function generateReaders(int $count): void
    {
        $existing = DB::table('readers')->count();
        if ($existing >= $count) {
            $this->info("Lasītāji: jau $existing");
            return;
        }
        $needed = $count - $existing;
        $this->info("Ģenerē $needed lasītāju...");

        $bar = $this->output->createProgressBar($needed);
        $bar->start();

        $chunk = 500;
        $rows = [];
        $ts = now()->timestamp;
        for ($i = 1; $i <= $needed; $i++) {
            $first = $this->lvFirstNames[array_rand($this->lvFirstNames)];
            $last = $this->lvLastNames[array_rand($this->lvLastNames)];
            $rows[] = [
                'name' => "$first $last",
                'email' => strtolower($first . '.' . $last . $i . '@inbox.lv'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if ($i % $chunk === 0 || $i === $needed) {
                DB::table('readers')->insert($rows);
                $rows = [];
                $bar->advance(min($chunk, $i % $chunk === 0 ? $chunk : $i));
            }
        }
        $bar->finish();
        $this->newLine();
    }

    private function weightedAvailableCopies(): int
    {
        $r = rand(1, 100);
        return match (true) {
            $r <= 5  => 0,           // 5% — reference/rare
            $r <= 35 => 1,           // 30% — single copy
            $r <= 65 => 2,           // 30% — two copies
            $r <= 83 => 3,           // 18% — three copies
            $r <= 93 => 4,           // 10% — four copies
            default  => rand(5, 8),  // 7% — popular, many copies
        };
    }

    private function generateBorrowings(int $count): void
    {
        $existing = DB::table('borrowings')->count();
        if ($existing >= $count) {
            $this->info("Aizņēmumi: jau $existing");
            return;
        }
        $needed = $count - $existing;
        $this->info("Ģenerē $needed aizņēmumu...");

        $bookIds = DB::table('books')->pluck('id')->toArray();
        $readerIds = DB::table('readers')->pluck('id')->toArray();

        if (empty($bookIds) || empty($readerIds)) {
            $this->error('Nav grāmatu vai lasītāju!');
            return;
        }

        $this->info('Veidoju svaru sadalījumu...');

        $bookPool = [];
        foreach ($bookIds as $id) {
            $r = rand(1, 100);
            if ($r <= 10) {
                $weight = rand(30, 60);
            } elseif ($r <= 60) {
                $weight = rand(8, 20);
            } else {
                $weight = rand(1, 5);
            }
            for ($j = 0; $j < $weight; $j++) {
                $bookPool[] = $id;
            }
        }

        $readerPool = [];
        foreach ($readerIds as $id) {
            $r = rand(1, 100);
            if ($r <= 15) {
                $weight = rand(30, 60);
            } elseif ($r <= 50) {
                $weight = rand(15, 30);
            } else {
                $weight = rand(3, 15);
            }
            for ($j = 0; $j < $weight; $j++) {
                $readerPool[] = $id;
            }
        }

        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_before_insert ON borrowings');
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_insert ON borrowings');
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_return ON borrowings');
            DB::statement('DROP FUNCTION IF EXISTS check_borrowing_available_copies CASCADE');
            DB::statement('DROP FUNCTION IF EXISTS decrement_available_copies CASCADE');
            DB::statement('DROP FUNCTION IF EXISTS increment_available_copies CASCADE');
        } else {
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_before_insert');
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_insert');
            DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_return');
        }

        $bar = $this->output->createProgressBar($needed);
        $bar->start();

        $chunk = 500;
        $now = now();
        $rows = [];

        for ($i = 1; $i <= $needed; $i++) {
            $durationR = rand(1, 100);
            if ($durationR <= 20) {
                $minD = 7;  $maxD = 14;
            } elseif ($durationR <= 50) {
                $minD = 14; $maxD = 21;
            } elseif ($durationR <= 80) {
                $minD = 21; $maxD = 30;
            } elseif ($durationR <= 95) {
                $minD = 30; $maxD = 60;
            } else {
                $minD = 60; $maxD = 180;
            }

            $borrowedAt = $now->copy()->subDays(rand(1, 180))->toDateString();
            $isReturned = rand(1, 100) <= 75;

            if ($isReturned) {
                $duration = rand($minD, $maxD);
                $returnedTs = strtotime($borrowedAt . " +$duration days");
                $returned = $returnedTs > time() ? $now->toDateString() : date('Y-m-d', $returnedTs);
            } else {
                $returned = null;
            }

            $dueDate = date('Y-m-d', strtotime($borrowedAt . ' + 30 days'));

            $rows[] = [
                'book_id' => $bookPool[array_rand($bookPool)],
                'reader_id' => $readerPool[array_rand($readerPool)],
                'borrowed_at' => $borrowedAt,
                'due_date' => $dueDate,
                'returned_at' => $returned,
                'created_at' => (string) $now,
                'updated_at' => (string) $now,
            ];

            if ($i % $chunk === 0 || $i === $needed) {
                DB::table('borrowings')->insert($rows);
                $rows = [];
                $bar->advance(min($chunk, $i % $chunk === 0 ? $chunk : $i));
            }
        }
        $bar->finish();
        $this->newLine();

        $this->info('Atjaunoju available_copies...');
        if ($driver === 'pgsql') {
            DB::statement('UPDATE books SET available_copies = GREATEST(0, available_copies - (SELECT COUNT(*) FROM borrowings WHERE borrowings.book_id = books.id AND borrowings.returned_at IS NULL))');
        } else {
            DB::statement('UPDATE books SET available_copies = MAX(0, available_copies - (SELECT COUNT(*) FROM borrowings WHERE borrowings.book_id = books.id AND borrowings.returned_at IS NULL))');
        }

        if ($driver === 'pgsql') {
            DB::statement('
                CREATE OR REPLACE FUNCTION check_borrowing_available_copies()
                RETURNS TRIGGER AS $$
                BEGIN
                    IF (SELECT available_copies FROM books WHERE id = NEW.book_id) <= 0 THEN
                        RAISE EXCEPTION \'Nav pieejamu eksemplāru\';
                    END IF;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement('
                CREATE TRIGGER trg_borrowings_before_insert
                BEFORE INSERT ON borrowings
                FOR EACH ROW
                EXECUTE FUNCTION check_borrowing_available_copies()
            ');
            DB::statement('
                CREATE OR REPLACE FUNCTION decrement_available_copies()
                RETURNS TRIGGER AS $$
                BEGIN
                    UPDATE books
                    SET available_copies = available_copies - 1,
                        updated_at = NOW()
                    WHERE id = NEW.book_id;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement('
                CREATE TRIGGER trg_borrowings_after_insert
                AFTER INSERT ON borrowings
                FOR EACH ROW
                EXECUTE FUNCTION decrement_available_copies()
            ');
            DB::statement('
                CREATE OR REPLACE FUNCTION increment_available_copies()
                RETURNS TRIGGER AS $$
                BEGIN
                    UPDATE books
                    SET available_copies = available_copies + 1,
                        updated_at = NOW()
                    WHERE id = NEW.book_id;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement('
                CREATE TRIGGER trg_borrowings_after_return
                AFTER UPDATE ON borrowings
                FOR EACH ROW
                WHEN (NEW.returned_at IS NOT NULL AND OLD.returned_at IS NULL)
                EXECUTE FUNCTION increment_available_copies()
            ');
        } else {
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_borrowings_before_insert
                BEFORE INSERT ON borrowings
                BEGIN
                    SELECT CASE
                        WHEN (SELECT available_copies FROM books WHERE id = NEW.book_id) <= 0 THEN
                            RAISE(ABORT, \'Nav pieejamu eksemplāru\')
                    END;
                END
            ');
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_borrowings_after_insert
                AFTER INSERT ON borrowings
                BEGIN
                    UPDATE books
                    SET available_copies = available_copies - 1,
                        updated_at = datetime(\'now\')
                    WHERE id = NEW.book_id;
                END
            ');
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_borrowings_after_return
                AFTER UPDATE ON borrowings
                WHEN NEW.returned_at IS NOT NULL AND OLD.returned_at IS NULL
                BEGIN
                    UPDATE books
                    SET available_copies = available_copies + 1,
                        updated_at = datetime(\'now\')
                    WHERE id = NEW.book_id;
                END
            ');
        }
    }

    private function generateReservations(int $count): void
    {
        $existing = DB::table('reservations')->count();
        if ($existing >= $count) {
            $this->info("Rezervācijas: jau $existing");
            return;
        }
        $needed = $count - $existing;
        $this->info("Ģenerē $needed rezervāciju...");

        $bookIds = DB::table('books')->pluck('id')->toArray();
        $readerIds = DB::table('readers')->pluck('id')->toArray();

        if (empty($bookIds) || empty($readerIds)) {
            $this->error('Nav grāmatu vai lasītāju!');
            return;
        }

        $bar = $this->output->createProgressBar($needed);
        $bar->start();

        $chunk = 500;
        $rows = [];
        $now = now();

        for ($i = 1; $i <= $needed; $i++) {
            $reservedAt = $now->copy()->subDays(rand(1, 60))->toDateString();
            $statusR = rand(1, 100);

            if ($statusR <= 60) {
                $status = 'pending';
                $cancelledAt = null;
            } elseif ($statusR <= 85) {
                $status = 'cancelled';
                $cancelledAt = date('Y-m-d', strtotime($reservedAt . ' + ' . rand(1, 14) . ' days'));
            } else {
                $status = 'fulfilled';
                $cancelledAt = null;
            }

            $rows[] = [
                'book_id' => $bookIds[array_rand($bookIds)],
                'reader_id' => $readerIds[array_rand($readerIds)],
                'reserved_at' => $reservedAt,
                'status' => $status,
                'cancelled_at' => $cancelledAt,
                'created_at' => (string) $now,
                'updated_at' => (string) $now,
            ];

            if ($i % $chunk === 0 || $i === $needed) {
                DB::table('reservations')->insert($rows);
                $rows = [];
                $bar->advance(min($chunk, $i % $chunk === 0 ? $chunk : $i));
            }
        }
        $bar->finish();
        $this->newLine();
    }

    private function generateFines(): void
    {
        DB::table('fines')->truncate();

        $overdueCount = DB::table('reader_fines')->count();
        if ($overdueCount === 0) {
            $this->info("Sodi: nav kavētu aizņēmumu");
            return;
        }

        $this->info("Ģenerē $overdueCount sodus...");
        $now = now()->toDateTimeString();
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                INSERT INTO fines (borrowing_id, reader_id, amount, reason, paid_at, created_at, updated_at)
                SELECT
                    rf.borrowing_id,
                    rf.reader_id,
                    rf.fine_amount,
                    'Kavējums par grāmatu: ' || rf.book_title || ' (' || rf.days_overdue || ' dienas)',
                    CASE WHEN FLOOR(RANDOM() * 100) < 15
                        THEN NOW() - ((FLOOR(RANDOM() * 30)::INT + 1) || ' days')::INTERVAL
                        ELSE NULL
                    END,
                    '$now'::TIMESTAMP,
                    '$now'::TIMESTAMP
                FROM reader_fines rf
            ");
        } else {
            DB::statement("
                INSERT INTO fines (borrowing_id, reader_id, amount, reason, paid_at, created_at, updated_at)
                SELECT
                    rf.borrowing_id,
                    rf.reader_id,
                    rf.fine_amount,
                    'Kavējums par grāmatu: ' || rf.book_title || ' (' || rf.days_overdue || ' dienas)',
                    CASE WHEN ABS(RANDOM() % 100) < 15
                        THEN datetime('now', '-' || (ABS(RANDOM() % 30) + 1) || ' days')
                        ELSE NULL
                    END,
                    '$now',
                    '$now'
                FROM reader_fines rf
            ");
        }
        $this->info(DB::table('fines')->count() . ' sodi ierakstīti.');
    }

    private function generateTitle(int $i): string
    {
        if ($i <= count($this->bookTitles)) {
            return $this->bookTitles[$i - 1];
        }
        return match (rand(0, 4)) {
            0 => $this->prefixes[array_rand($this->prefixes)] . ' ' . $this->bookTitles[array_rand($this->bookTitles)],
            1 => $this->bookTitles[array_rand($this->bookTitles)] . ': ' . $this->suffixes[array_rand($this->suffixes)],
            2 => $this->bookTitles[array_rand($this->bookTitles)] . ' (' . rand(1900, 2026) . ')',
            3 => $this->bookTitles[array_rand($this->bookTitles)] . ', ' . $this->bookTitles[array_rand($this->bookTitles)],
            default => $this->prefixes[array_rand($this->prefixes)] . ' ' . $this->bookTitles[array_rand($this->bookTitles)],
        };
    }
}
