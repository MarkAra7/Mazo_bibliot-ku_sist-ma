<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('old_copies');
            $table->unsignedInteger('new_copies');
            $table->string('operation', 10)->default('UPDATE');
            $table->timestamp('changed_at')->useCurrent();

            $table->index('book_id');
            $table->index('changed_at');
        });

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('
                CREATE OR REPLACE FUNCTION log_book_changes()
                RETURNS TRIGGER AS $$
                BEGIN
                    INSERT INTO book_log (book_id, old_copies, new_copies, operation, changed_at)
                    VALUES (OLD.id, OLD.available_copies, NEW.available_copies, \'UPDATE\', NOW());
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement("
                CREATE TRIGGER trg_book_log_after_update
                AFTER UPDATE ON books
                FOR EACH ROW
                WHEN (OLD.available_copies IS DISTINCT FROM NEW.available_copies)
                EXECUTE FUNCTION log_book_changes()
            ");
        } else {
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_book_log_after_update
                AFTER UPDATE ON books
                WHEN OLD.available_copies IS NOT NEW.available_copies
                BEGIN
                    INSERT INTO book_log (book_id, old_copies, new_copies, operation, changed_at)
                    VALUES (OLD.id, OLD.available_copies, NEW.available_copies, \'UPDATE\', datetime(\'now\'));
                END
            ');
        }
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_book_log_after_update');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP FUNCTION IF EXISTS log_book_changes CASCADE');
        }
        Schema::dropIfExists('book_log');
    }
};
