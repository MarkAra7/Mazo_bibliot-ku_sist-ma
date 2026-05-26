<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reader_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->text('reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['reader_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
