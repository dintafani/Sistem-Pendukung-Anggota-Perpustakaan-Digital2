<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ==========================================================
     * BUAT TABEL JURNAL BACAAN
     * ==========================================================
     */
    public function up(): void
    {
        Schema::create('reading_journals', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PRIMARY KEY
            |--------------------------------------------------------------------------
            */
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELASI PEMINJAMAN
            |--------------------------------------------------------------------------
            |
            | Satu transaksi peminjaman hanya memiliki satu jurnal bacaan.
            |
            */
            $table->foreignId('borrowing_id')
                ->constrained('borrowings')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | RELASI SISWA
            |--------------------------------------------------------------------------
            |
            | Menyimpan siswa yang mengisi jurnal.
            |
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | RELASI BUKU
            |--------------------------------------------------------------------------
            |
            | Menyimpan buku yang dibaca.
            |
            */
            $table->foreignId('book_id')
                ->constrained('books')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ISI JURNAL
            |--------------------------------------------------------------------------
            */

            $table->text('ringkasan')
                ->nullable();

            $table->text('yang_dipelajari')
                ->nullable();

            $table->text('bagian_menarik')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | RATING
            |--------------------------------------------------------------------------
            |
            | Nilai rating 1 - 5.
            |
            */
            $table->unsignedTinyInteger('rating')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | ULASAN
            |--------------------------------------------------------------------------
            */
            $table->text('ulasan')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | STATUS JURNAL
            |--------------------------------------------------------------------------
            |
            | draft      = jurnal belum selesai
            | menunggu   = jurnal sudah dikirim siswa
            | disetujui  = jurnal sudah diverifikasi petugas
            | ditolak    = jurnal perlu diperbaiki
            |
            */
            $table->enum('status', [
                'draft',
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | CATATAN PETUGAS
            |--------------------------------------------------------------------------
            */
            $table->text('catatan_petugas')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | PETUGAS YANG MEMVERIFIKASI
            |--------------------------------------------------------------------------
            |
            | NULL jika belum diverifikasi.
            |
            */
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | WAKTU VERIFIKASI
            |--------------------------------------------------------------------------
            */
            $table->timestamp('verified_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | TIMESTAMP
            |--------------------------------------------------------------------------
            */
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | SATU PEMINJAMAN = SATU JURNAL
            |--------------------------------------------------------------------------
            |
            | Mencegah satu transaksi memiliki lebih dari satu jurnal.
            |
            */
            $table->unique('borrowing_id');
        });
    }

    /**
     * ==========================================================
     * HAPUS TABEL JURNAL BACAAN
     * ==========================================================
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_journals');
    }
};