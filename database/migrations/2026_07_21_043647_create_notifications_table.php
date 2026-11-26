<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('judul');

            $table->text('pesan');

            $table->string('jenis')->nullable();

            $table->boolean('dibaca')
                ->default(false);

            $table->timestamps();

        });
    }


    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};