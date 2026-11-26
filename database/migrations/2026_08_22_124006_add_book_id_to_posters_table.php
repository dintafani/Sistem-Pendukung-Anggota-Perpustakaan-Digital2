<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('posters', function (Blueprint $table) {

            $table->foreignId('book_id')
                ->nullable()
                ->after('id')
                ->constrained('books')
                ->nullOnDelete();

        });
    }

    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('posters', function (Blueprint $table) {

            $table->dropForeign(['book_id']);

            $table->dropColumn('book_id');

        });
    }
};