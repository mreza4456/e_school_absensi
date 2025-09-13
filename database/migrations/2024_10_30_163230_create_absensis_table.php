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
        Schema::create('absensis', function (Blueprint $table) {
            // $table->uuid('id')->primary()->default(Str::uuid());
            $table->id();
            $table->foreignUuid('members_id')->nullable()->constrained('members')->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('keterangan', ['Masuk', 'Pulang', 'Terlambat', 'Izin', 'Sakit', 'Alpa'])->default('Alpha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
