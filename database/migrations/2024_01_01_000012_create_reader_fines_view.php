<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS reader_fines AS
            SELECT
                r.id AS reader_id,
                r.name AS reader_name,
                b.id AS borrowing_id,
                bo.title AS book_title,
                b.borrowed_at,
                date(b.borrowed_at, '+14 days') AS due_date,
                CAST(julianday('now') - julianday(b.borrowed_at) - 14 AS INTEGER) AS days_overdue,
                round(CAST(julianday('now') - julianday(b.borrowed_at) - 14 AS INTEGER) * 0.50, 2) AS fine_amount
            FROM readers r
            JOIN borrowings b ON b.reader_id = r.id
            JOIN books bo ON bo.id = b.book_id
            WHERE b.returned_at IS NULL
              AND julianday('now') - julianday(b.borrowed_at) > 14
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS reader_fines');
    }
};
