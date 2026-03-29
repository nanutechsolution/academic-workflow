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

        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('color')->default('primary'); // success, warning, danger
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('category'); // SK, Surat Tugas, dll
            $table->timestamps();
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->boolean('is_archived')->default(false);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('action'); // View, Download, Edit
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('document_templates');
        Schema::dropIfExists('academic_events');
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('is_archived');
        });
    }
};
