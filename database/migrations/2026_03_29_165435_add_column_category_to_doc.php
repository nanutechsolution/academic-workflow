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
        Schema::table('documents', function (Blueprint $table) {
            // Menambahkan relasi ke kategori dokumen (Surat Tugas, SK, dll)
            $table->foreignId('document_category_id')
                ->nullable()
                ->after('id') // Diletakkan tepat setelah kolom ID agar struktur tabel rapi
                ->constrained('document_categories')
                ->nullOnDelete();

            // Menambahkan kolom string untuk menyimpan nomor surat terformat
            $table->string('document_number')
                ->nullable()
                ->unique() // Memastikan tidak ada nomor surat ganda di UNMARIS
                ->after('document_category_id');

            // ⚡ Catatan: Penomoran otomatis akan di-handle di level aplikasi (Filament) untuk memastikan nomor terformat sesuai dengan pola yang ditentukan per kategori.
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Menghapus foreign key dan kolom jika migrasi di-rollback
            $table->dropForeign(['document_category_id']);
            $table->dropColumn(['document_category_id', 'document_number']);
        });
    }
};
