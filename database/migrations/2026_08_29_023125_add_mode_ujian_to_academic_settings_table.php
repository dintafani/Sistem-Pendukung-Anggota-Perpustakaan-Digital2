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
        Schema::table('academic_settings', function (Blueprint $table) {

            $table->boolean('mode_ujian')
                ->default(false)
                ->after('aktif');

        });
    }


    /**
     * Batalkan migration.
     */
    public function down(): void
    {
        Schema::table('academic_settings', function (Blueprint $table) {

            $table->dropColumn('mode_ujian');

        });
    }
};