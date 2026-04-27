<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pelaporan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan')->unique();
            $table->foreignId('usaha_id')->constrained('usaha')->onDelete('cascade');
            $table->string('bulan');
            $table->year('tahun');
            $table->decimal('omset', 15, 2);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaporan');
    }
};
