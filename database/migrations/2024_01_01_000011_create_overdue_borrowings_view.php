<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS overdue_borrowings');

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                CREATE VIEW overdue_borrowings AS
                SELECT
                    b.id AS borrowing_id,
                    bo.title AS book_title,
                    bo.isbn,
                    r.name AS reader_name,
                    r.email AS reader_email,
                    b.borrowed_at,
                    (b.borrowed_at + INTERVAL '14 days')::DATE AS due_date,
                    (CURRENT_DATE - b.borrowed_at) - 14 AS days_overdue
                FROM borrowings b
                JOIN books bo ON bo.id = b.book_id
                JOIN readers r ON r.id = b.reader_id
                WHERE b.returned_at IS NULL
                  AND (b.borrowed_at + INTERVAL '14 days')::DATE < CURRENT_DATE
            ");
        } else {
            DB::statement("
                CREATE VIEW overdue_borrowings AS
                SELECT
                    b.id AS borrowing_id,
                    bo.title AS book_title,
                    bo.isbn,
                    r.name AS reader_name,
                    r.email AS reader_email,
                    b.borrowed_at,
                    date(b.borrowed_at, '+14 days') AS due_date,
                    CAST(julianday('now') - julianday(b.borrowed_at) - 14 AS INTEGER) AS days_overdue
                FROM borrowings b
                JOIN books bo ON bo.id = b.book_id
                JOIN readers r ON r.id = b.reader_id
                WHERE b.returned_at IS NULL
                  AND date(b.borrowed_at, '+14 days') < date('now')
            ");
        }
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS overdue_borrowings');
    }
};
