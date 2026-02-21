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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('paramedis_id')->nullable()->constrained('paramedis')->nullOnDelete();
            $table->string('no_transaksi')->nullable();
            $table->string('tgl_transaksi')->nullable();
            $table->string('tinggi_badan')->nullable();
            $table->string('berat_badan')->nullable();
            $table->string('tensi')->nullable();
            $table->string('suhu')->nullable();
            $table->string('saturnasi')->nullable();
            $table->string('denyutnadi')->nullable();
            $table->string('foto');
            $table->enum('gol_darah', ['A', 'B', 'O', 'AB', '-']);
            $table->bigInteger('buta_warna')->default(false);
            $table->bigInteger('pendengaran')->default(false);
            $table->bigInteger('status_kesehatan')->default(true);
            $table->text('keperluan')->nullable();
            $table->bigInteger('is_tagih')->default(false);
            $table->bigInteger('is_bayar')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
