<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->unique()->constrained('distribusi')->cascadeOnDelete();
            $table->foreignId('penerima_id')->constrained('penerima')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5, validasi di FormRequest
            $table->text('isi_ulasan')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};