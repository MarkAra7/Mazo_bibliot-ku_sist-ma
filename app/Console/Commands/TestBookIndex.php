<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestBookIndex extends Command
{
    protected $signature = 'books:test-index {count=10000}';
    protected $description = 'Ģenerē testa grāmatas un testē indeksa veiktspēju';

    public function handle(): void
    {
        $count = (int) $this->argument('count');
        $this->info("Ģenerē $count testa grāmatas...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $chunk = 500;
        $rows = [];
        $isbnBase = 'TEST-' . now()->timestamp . '-';

        for ($i = 1; $i <= $count; $i++) {
            $rows[] = [
                'title' => "Testa grāmata Nr. $i ar garāku nosaukumu un aprakstu",
                'isbn' => $isbnBase . str_pad((string) $i, 6, '0', STR_PAD_LEFT),
                'available_copies' => rand(0, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($i % $chunk === 0 || $i === $count) {
                DB::table('books')->insert($rows);
                $rows = [];
                $bar->advance($i % $chunk === 0 ? $chunk : $i % $chunk);
            }
        }

        $bar->finish();

        $total = DB::table('books')->count();
        $this->newLine();
        $this->info("Kopā grāmatas: $total");

        // Testējam meklēšanu
        $this->newLine();
        $this->info('Meklējam: "Testa grāmata Nr. 7777 ar garāku nosaukumu un aprakstu"');

        // 1. EXPLAIN QUERY PLAN
        $plan = DB::select('EXPLAIN QUERY PLAN SELECT * FROM books WHERE title = ?', [
            'Testa grāmata Nr. 7777 ar garāku nosaukumu un aprakstu',
        ]);

        $this->newLine();
        $this->line('--- EXPLAIN QUERY PLAN ---');
        foreach ($plan as $row) {
            $this->line($row->detail ?? json_encode((array) $row));
        }

        // 2. Izmērīt laiku (5 reizes)
        $times = [];
        for ($r = 0; $r < 5; $r++) {
            $start = microtime(true);
            $result = DB::select('SELECT * FROM books WHERE title = ?', [
                'Testa grāmata Nr. 7777 ar garāko nosaukumu un aprakstu',
            ]);
            $times[] = (microtime(true) - $start) * 1000;
        }
        $this->newLine();
        $this->line('--- Meklēšanas laiks (5 reizes) ---');
        foreach ($times as $i => $t) {
            $this->line('  ' . ($i + 1) . '. ' . number_format($t, 3) . ' ms');
        }
        $avg = array_sum($times) / count($times);
        $this->info("Vidējais: " . number_format($avg, 3) . " ms");

        // 3. EXPLAIN LIKE meklēšanai
        $planLike = DB::select('EXPLAIN QUERY PLAN SELECT * FROM books WHERE title LIKE ?', ['%7777%']);
        $this->newLine();
        $this->line('--- EXPLAIN QUERY PLAN (LIKE %...) ---');
        foreach ($planLike as $row) {
            $this->line($row->detail ?? json_encode((array) $row));
        }
    }
}
