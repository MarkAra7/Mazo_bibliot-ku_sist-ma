<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('isbn', 20)->unique();
            $table->unsignedInteger('available_copies')->default(1);
            $table->timestamps();

            $table->index('title');
            $table->index('available_copies');
        });

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('
                CREATE OR REPLACE FUNCTION set_book_timestamps()
                RETURNS TRIGGER AS $$
                BEGIN
                    NEW.created_at = COALESCE(NEW.created_at, NOW());
                    NEW.updated_at = NOW();
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement('
                CREATE TRIGGER trg_books_after_insert
                BEFORE INSERT ON books
                FOR EACH ROW
                EXECUTE FUNCTION set_book_timestamps()
            ');
            DB::statement('
                CREATE OR REPLACE FUNCTION prevent_negative_copies()
                RETURNS TRIGGER AS $$
                BEGIN
                    IF NEW.available_copies < 0 THEN
                        RAISE EXCEPTION \'Pieejamo eksemplāru skaits nevar būt negatīvs\';
                    END IF;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement("
                CREATE TRIGGER trg_books_prevent_negative_copies
                BEFORE UPDATE ON books
                FOR EACH ROW
                EXECUTE FUNCTION prevent_negative_copies()
            ");
        } else {
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_books_after_insert
                AFTER INSERT ON books
                BEGIN
                    UPDATE books SET created_at = COALESCE(NEW.created_at, datetime(\'now\')),
                                     updated_at = datetime(\'now\')
                    WHERE rowid = NEW.rowid;
                END
            ');

            DB::statement("
                CREATE TRIGGER IF NOT EXISTS trg_books_prevent_negative_copies
                BEFORE UPDATE ON books
                BEGIN
                    SELECT CASE
                        WHEN NEW.available_copies < 0 THEN
                            RAISE(ABORT, 'Pieejamo eksemplāru skaits nevar būt negatīvs')
                    END;
                END
            ");
        }
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_books_after_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_books_prevent_negative_copies');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP FUNCTION IF EXISTS set_book_timestamps CASCADE');
            DB::statement('DROP FUNCTION IF EXISTS prevent_negative_copies CASCADE');
        }
        Schema::dropIfExists('books');
    }
};
