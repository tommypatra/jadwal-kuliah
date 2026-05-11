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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->unsignedBigInteger('dosen_siakad_id')->unique();

            $table->unsignedBigInteger('program_studi_siakad_id')
                ->nullable();

            $table->string('nama', 180);
            $table->string('gelar_depan', 30)
                ->nullable();
            $table->string('gelar_belakang', 50)
                ->nullable();
            $table->string('nip', 50)
                ->nullable();
            $table->string('nidn', 50)
                ->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])
                ->nullable();

            $table->string('email', 180)
                ->nullable();

            $table->string('email_kampus', 180)
                ->nullable();

            $table->string('nomor_hp', 180)
                ->nullable();

            $table->string('status_aktif', 50)
                ->nullable();

            $table->timestamp('updated_at_siakad')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
