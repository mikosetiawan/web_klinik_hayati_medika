<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_diagnosa_id')->constrained('hasil_diagnosas')->onDelete('cascade');
            $table->decimal('total_harga', 10, 2);
            $table->date('tanggal_pembayaran');
            $table->enum('metode_pembayaran', ['cash', 'bank_transfer']);
            $table->enum('status_pembayaran', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
