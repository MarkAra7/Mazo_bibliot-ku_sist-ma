<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reader_id')->constrained()->cascadeOnDelete();
            $table->date('borrowed_at');
            $table->date('returned_at')->nullable();
            $table->timestamps();

            $table->index('borrowed_at');
            $table->index('returned_at');
            $table->index(['book_id', 'returned_at']);
            $table->index(['reader_id', 'returned_at']);
        });

        $driver = DB::getDriverName();

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

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_before_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_borrowings_after_return');
        Schema::dropIfExists('borrowings');
    }
};
