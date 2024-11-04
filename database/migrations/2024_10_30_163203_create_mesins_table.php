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
        Schema::create('mesins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('sekolah_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('kode_mesin')->unique();
            $table->date('tgl_pembuatan')->nullable();
            $table->string('idle')->nullable();
            $table->enum('keterangan', ['Sudah Aktif', 'Belum Diset', 'Tidak Aktif'])->default('Belum Diset');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesins');
    }
};
