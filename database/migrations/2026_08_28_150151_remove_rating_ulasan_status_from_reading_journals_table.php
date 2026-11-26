<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan perubahan database.
     */
    public function up(): void
    {
        Schema::table('reading_journals', function (Blueprint $table) {
            $table->dropColumn([
                'rating',
                'ulasan',
                'status',
            ]);
        });
    }

    /**
     * Membatalkan perubahan database.
     */
    public function down(): void
    {
        Schema::table('reading_journals', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable();
            $table->text('ulasan')->nullable();

            $table->enum('status', [
                'draft',
                'menunggu',
                'disetujui',
                'ditolak',
            ])->default('draft');
        });
    }
};