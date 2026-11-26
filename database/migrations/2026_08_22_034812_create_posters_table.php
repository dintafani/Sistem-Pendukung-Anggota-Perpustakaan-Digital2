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
        Schema::create('posters', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | JUDUL POSTER
            |--------------------------------------------------------------------------
            */
            $table->string('judul');

            /*
            |--------------------------------------------------------------------------
            | DESKRIPSI
            |--------------------------------------------------------------------------
            */
            $table->text('deskripsi')->nullable();

            /*
            |--------------------------------------------------------------------------
            | GAMBAR POSTER
            |--------------------------------------------------------------------------
            | Contoh:
            | posters/poster-abc123.jpg
            |--------------------------------------------------------------------------
            */
            $table->string('gambar');

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            | 1 = tampil
            | 0 = tidak tampil
            |--------------------------------------------------------------------------
            */
            $table->boolean('status')->default(true);

            /*
            |--------------------------------------------------------------------------
            | URUTAN
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger('urutan')->default(0);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posters');
    }
};