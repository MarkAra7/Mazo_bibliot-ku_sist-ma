<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('readers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamps();

            $table->index('name');
            $table->index('email');
        });

        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('
                CREATE OR REPLACE FUNCTION set_reader_timestamps()
                RETURNS TRIGGER AS $$
                BEGIN
                    NEW.created_at = COALESCE(NEW.created_at, NOW());
                    NEW.updated_at = NOW();
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            ');
            DB::statement('
                CREATE TRIGGER trg_readers_after_insert
                BEFORE INSERT ON readers
                FOR EACH ROW
                EXECUTE FUNCTION set_reader_timestamps()
            ');
        } else {
            DB::statement('
                CREATE TRIGGER IF NOT EXISTS trg_readers_after_insert
                AFTER INSERT ON readers
                BEGIN
                    UPDATE readers SET created_at = COALESCE(NEW.created_at, datetime(\'now\')),
                                       updated_at = datetime(\'now\')
                    WHERE rowid = NEW.rowid;
                END
            ');
        }
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trg_readers_after_insert');
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP FUNCTION IF EXISTS set_reader_timestamps CASCADE');
        }
        Schema::dropIfExists('readers');
    }
};
