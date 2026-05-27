<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS reader_fines');

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                CREATE VIEW reader_fines AS
                SELECT
                    r.id AS reader_id,
                    r.name AS reader_name,
                    b.id AS borrowing_id,
                    bo.title AS book_title,
                    b.borrowed_at,
                    b.due_date,
                    (CURRENT_DATE - b.due_date) AS days_overdue,
                    ROUND((CURRENT_DATE - b.due_date) * 0.50, 2) AS fine_amount
                FROM readers r
                JOIN borrowings b ON b.reader_id = r.id
                JOIN books bo ON bo.id = b.book_id
                WHERE b.returned_at IS NULL
                  AND b.due_date IS NOT NULL
                  AND CURRENT_DATE > b.due_date
            ");
        } else {
            DB::statement("
                CREATE VIEW reader_fines AS
                SELECT
                    r.id AS reader_id,
                    r.name AS reader_name,
                    b.id AS borrowing_id,
                    bo.title AS book_title,
                    b.borrowed_at,
                    b.due_date,
                    CAST(julianday('now') - julianday(b.due_date) AS INTEGER) AS days_overdue,
                    round(CAST(julianday('now') - julianday(b.due_date) AS INTEGER) * 0.50, 2) AS fine_amount
                FROM readers r
                JOIN borrowings b ON b.reader_id = r.id
                JOIN books bo ON bo.id = b.book_id
                WHERE b.returned_at IS NULL
                  AND b.due_date IS NOT NULL
                  AND date('now') > b.due_date
            ");
        }
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS reader_fines');
    }
};
