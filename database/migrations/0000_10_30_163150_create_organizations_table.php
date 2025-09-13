<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Personal information
            $table->integer('npsn');
            $table->string('nama');
            $table->string('jenjang');
            $table->string('nik_kepala');
            $table->string('nama_kepala');

            // Address and contact
            $table->text('alamat');
            $table->integer('provinsi_code');
            $table->foreign('provinsi_code')->references('code')->on('provinsis')->onUpdate('cascade');
            $table->integer('kota_code');
            $table->foreign('kota_code')->references('code')->on('kotas')->onUpdate('cascade');
            $table->string('no_telp')->unique();
            $table->string('email')->unique();

            // details
            $table->string('logo')->nullable();
            $table->string('timezone');
            $table->boolean('status')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel jadwal per hari
        Schema::create('jadwal_harians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->enum('hari', ['Monday', 'Tuesday', 'Wednesday', 'Thurstday', 'Friday', 'Saturday', 'Sunday']);

            // Jam Masuk
            $table->time('jam_masuk')->nullable();
            $table->time('jam_masuk_selesai')->nullable(); // toleransi keterlambatan

            // Jam Istirahat
            $table->time('jam_istirahat')->nullable();
            $table->time('jam_istirahat_selesai')->nullable();

            // Jam Pulang
            $table->time('jam_pulang')->nullable();
            $table->time('jam_pulang_selesai')->nullable(); // toleransi kepulangan

            $table->boolean('is_libur')->default(false);
            $table->timestamps();

            // Memastikan setiap sekolah hanya memiliki satu setting untuk setiap hari
            $table->unique(['organization_id', 'hari']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_harians');
        Schema::dropIfExists('organizations');
    }
};
