<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('no_telepon', 15)->nullable();
            $table->text('alamat');
            $table->string('rt', 10);
            $table->enum('kategori', ['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya']);
            $table->text('deskripsi_kategori')->nullable(); // wajib jika kategori = lainnya
            $table->date('estimasi_durasi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerima');
    }
};