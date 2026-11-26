<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            // Relasi ke peminjaman
            $table->foreignId('borrowing_id')
                ->constrained()
                ->onDelete('cascade');

            // Relasi ke buku
            $table->foreignId('book_id')
                ->constrained()
                ->onDelete('cascade');

            // Relasi ke siswa
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Rating 1-5
            $table->tinyInteger('rating');

            // Ulasan
            $table->text('ulasan');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};