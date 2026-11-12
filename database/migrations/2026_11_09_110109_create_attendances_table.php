<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            // Relasi ke anggota
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Relasi ke kegiatan
            $table->foreignId('event_id')
                  ->constrained('events')
                  ->cascadeOnDelete();

            // Status kehadiran
            $table->enum('status', [
                'hadir',
                'izin',
                'sakit'
            ])->default('hadir');

            // created_at = waktu absensi
            $table->timestamps();

            // Mencegah anggota absen dua kali
            // pada kegiatan yang sama
            $table->unique(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
