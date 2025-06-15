<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_diagnosa_diagnosa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_diagnosa_id')->constrained('hasil_diagnosas')->onDelete('cascade');
            $table->foreignId('diagnosa_id')->constrained('diagnosas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_diagnosa_diagnosa');
    }
};