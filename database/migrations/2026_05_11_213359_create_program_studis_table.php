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
        Schema::create('program_studis', function (Blueprint $table) {
            $table->id();

            $table->string('nama_program_studi', 180);
            $table->string('nama_singkat_program_studi', 180);
            $table->string('nama_fakultas', 180);
            $table->unsignedBigInteger('program_studi_id_siakad')->nullable();
            $table->unique('program_studi_id_siakad');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_studis');
    }
};
