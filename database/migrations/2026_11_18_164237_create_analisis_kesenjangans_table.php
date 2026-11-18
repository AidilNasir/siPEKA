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
        Schema::create('analisis_kesenjangans', function (Blueprint $table) {
            $table->id();
            $table->string('id_monev')->unique()->index();
            $table->string('nip')->index();
            $table->text('standar_kompetensi')->nullable();
            $table->text('kompetensi_saat_ini')->nullable();
            $table->text('gap_kesenjangan')->nullable();
            $table->string('rencana_pengembangan')->nullable();
            $table->integer('target_jp')->default(20);
            $table->integer('jp_terealisasi')->default(0);
            $table->string('status')->default('Belum Memenuhi');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('pegawais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_kesenjangans');
    }
};
