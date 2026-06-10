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
        Schema::create('spj_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spj_id')->constrained('spjs')->onDelete('cascade');
            $table->foreignId('dokumen_pendukung_id')->constrained('dokumen_pendukungs')->onDelete('cascade');
            $table->string('file_path');
            $table->text('komentar_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spj_dokumens');
    }
};
