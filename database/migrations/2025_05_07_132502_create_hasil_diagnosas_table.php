<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_diagnosas', function (Blueprint $table) {
            $table->id();
            $table->string('no_rm', 20)->unique();
            $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_pendaftaran_pasien')->constrained('pendaftaran_pasiens')->onDelete('cascade');
            $table->text('catatan')->nullable();
            $table->date('tanggal_diagnosa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_diagnosas');
    }
};