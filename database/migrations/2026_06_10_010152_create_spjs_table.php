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
        Schema::create('spjs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_spj_id')->constrained('jenis_spjs')->onDelete('cascade');
            $table->string('deskripsi');
            $table->enum('filter_tipe', ['GU', 'TU'])->default('GU');
            $table->integer('filter_no')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->foreignId('rekening_id')->constrained('rekenings')->onDelete('cascade');
            $table->integer('status_level')->default(0);
            $table->boolean('is_rejected')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spjs');
    }
};
