<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_settings', function (Blueprint $table) {
            $table->unsignedInteger('batas_paket')
                ->default(0)
                ->after('tanggal_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('academic_settings', function (Blueprint $table) {
            $table->dropColumn('batas_paket');
        });
    }
};