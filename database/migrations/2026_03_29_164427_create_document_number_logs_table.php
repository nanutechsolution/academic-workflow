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
        Schema::create('document_number_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke Dokumen (opsional jika nomor bisa dikeluarkan tanpa upload file dulu)
            $table->foreignId('document_id')->nullable()->constrained()->nullOnDelete();

            // Relasi ke kategori (SK, Surat Tugas, dll)
            $table->foreignId('document_category_id')->constrained();

            // Nomor surat final yang di-generate (Contoh: 001/UNMARIS/W1/03/2026)
            $table->string('formatted_number')->unique();

            // Urutan angka mentah (Contoh: 1) untuk keperluan reset tahunan
            $table->integer('sequence_number');

            // Siapa admin yang mengeluarkan nomor ini
            $table->foreignId('user_id')->constrained();

            // Perihal singkat (agar tetap bisa dibaca meski dokumen dihapus)
            $table->string('subject')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_number_logs');
    }
};
