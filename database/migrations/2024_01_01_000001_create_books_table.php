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

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_books_after_insert');
        DB::statement('DROP TRIGGER IF EXISTS trg_books_prevent_negative_copies');
        Schema::dropIfExists('books');
    }
};
