<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('isbn');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('branch_id');
        });
        Schema::dropIfExists('branches');
    }
};
