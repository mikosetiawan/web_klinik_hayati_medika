<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftaranPasiensTable extends Migration
{
    public function up()
    {
        Schema::create('pendaftaran_pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('no_antrian');
             $table->foreignId('id_dokter')->constrained('dokters')->onDelete('cascade');
            $table->foreignId('id_pasien')->constrained('pasiens')->onDelete('cascade');
            $table->date('tanggal_pendaftaran');
            $table->enum('status', ['booked', 'confirmed', 'diagnosis', 'finished', 'paid', 'cancelled'])->default('booked');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftaran_pasiens');
    }
}