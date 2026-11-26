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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Nama orang yang mengisi daftar hadir
            $table->string('nama');

            // Asal sekolah, instansi, atau organisasi
            $table->string('instansi')->nullable();

            // Jenis pengunjung
            $table->enum('jenis_pengunjung', [
                'Siswa',
                'Guru',
                'Petugas',
                'Anak Magang',
                'Tamu/Kunjungan',
                'Lainnya'
            ]);

            // Kelas hanya diperlukan jika pengunjung adalah siswa
            $table->string('kelas')->nullable();

            // Tujuan datang ke perpustakaan
            $table->text('tujuan')->nullable();

            // Waktu pengunjung mengisi daftar hadir
            $table->timestamp('waktu_masuk')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};