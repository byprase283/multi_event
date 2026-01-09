<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('kode_registrasi', 50)->nullable(); // Generated saat validasi
            $table->string('kode_voucher', 50)->nullable();
            $table->string('token_hash', 100)->nullable(); // Unique token untuk tiket
            $table->string('nama', 150);
            $table->enum('jenis_kelamin', ['Pria', 'Wanita']);
            $table->integer('usia');
            $table->string('whatsapp', 20);
            $table->text('alamat');
            $table->string('ukuran_jersey', 15)->nullable();
            $table->string('bukti_bayar', 255)->nullable();
            $table->datetime('waktu_daftar')->useCurrent();
            $table->datetime('tgl_validasi')->nullable();
            $table->enum('status_verifikasi', ['Pending', 'Valid', 'Invalid', 'Redeem'])->default('Pending');
            $table->timestamps();

            $table->unique('kode_registrasi');
            $table->unique('token_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
