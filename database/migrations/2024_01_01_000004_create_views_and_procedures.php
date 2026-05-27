<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('DROP VIEW IF EXISTS active_borrowings');

        DB::statement("
            CREATE VIEW active_borrowings AS
            SELECT
                b.id AS borrowing_id,
                bo.title AS book_title,
                bo.isbn,
                r.name AS reader_name,
                r.email AS reader_email,
                b.borrowed_at,
                b.returned_at
            FROM borrowings b
            JOIN books bo ON bo.id = b.book_id
            JOIN readers r ON r.id = b.reader_id
            WHERE b.returned_at IS NULL
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS active_borrowings');
    }
};
