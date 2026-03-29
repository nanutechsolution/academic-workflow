<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama Akreditasi
        Schema::create('accreditations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete(); // Relasi ke Prodi/Fakultas
            $table->string('name'); // Contoh: Akreditasi Prodi SI 2026
            $table->string('agency'); // BAN-PT, LAM-INFOKOM, dll
            $table->string('target_rank')->nullable(); // Unggul, Baik Sekali, Baik
            $table->enum('status', ['Persiapan', 'Submit SAPTO', 'Asesmen Kecukupan', 'Visitasi', 'Selesai'])->default('Persiapan');
            $table->date('expiry_date')->nullable(); // Masa berlaku akreditasi saat ini
            $table->date('target_date')->nullable(); // Target submit borang
            $table->json('criteria_progress')->nullable(); // Menyimpan progress 9 Kriteria (JSON)
            $table->timestamps();
        });

        // Tabel Dokumen Bukti (Eviden Borang)
        Schema::create('accreditation_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accreditation_id')->constrained()->cascadeOnDelete();
            $table->string('criterion'); // Kriteria 1 s/d 9
            $table->string('title'); // Nama Dokumen (e.g. SK Dekan tentang Kurikulum)
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accreditation_evidences');
        Schema::dropIfExists('accreditations');
    }
};