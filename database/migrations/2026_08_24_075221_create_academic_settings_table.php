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
        Schema::create('academic_settings', function (Blueprint $table) {

            $table->id();

            // Tahun ajaran
            // Contoh: 2026/2027
            $table->string('tahun_ajaran', 20);

            // Semester:
            // 1 = Semester 1
            // 2 = Semester 2
            $table->unsignedTinyInteger('semester');

            // Tanggal awal periode semester
            $table->date('tanggal_mulai');

            // Tanggal akhir periode semester
            $table->date('tanggal_selesai');

            // Menentukan semester yang sedang aktif
            // true  = aktif
            // false = tidak aktif
            $table->boolean('aktif')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};