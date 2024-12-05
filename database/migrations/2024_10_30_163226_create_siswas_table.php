<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sekolah_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('kelas_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('nis')->unique();
            $table->string('nama');
            $table->string('panggilan');
            $table->enum('jk', ['L', 'P']);
            $table->string('telp_ortu');
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
