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
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users'); // Penulis Disposisi (Warek)
            $table->foreignId('to_user_id')->constrained('users');   // Penerima Instruksi (Dekan)
            $table->text('content'); // Isi Instruksi
            $table->string('status')->default('pending'); // pending, read, completed
            $table->datetime('due_date')->nullable(); // Tenggat waktu tindak lanjut
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositions');
    }
};
