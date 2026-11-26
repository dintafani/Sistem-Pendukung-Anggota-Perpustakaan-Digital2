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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa');
        $table->string('status')->default('pending');
        $table->string('barcode')->nullable();
        $table->integer('poin')->default(0);
        $table->string('nis')->nullable();
        $table->string('kelas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn([
            'role',
            'status',
            'barcode',
            'poin',
            'nis',
            'kelas'
        ]);
        });
    }
};
