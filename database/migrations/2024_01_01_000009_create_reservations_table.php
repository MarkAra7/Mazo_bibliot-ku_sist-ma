<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reader_id')->constrained()->cascadeOnDelete();
            $table->date('reserved_at');
            $table->string('status')->default('pending'); // pending, fulfilled, cancelled
            $table->foreignId('fulfilled_by_borrowing_id')->nullable()->constrained('borrowings')->nullOnDelete();
            $table->date('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['book_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
