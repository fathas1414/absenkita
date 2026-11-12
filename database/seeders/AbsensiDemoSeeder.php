<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AbsensiDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Password diambil dari .env lokal
        $password = env('ABSENKITA_DEMO_PASSWORD');

        if (!is_string($password) || strlen($password) < 12) {
            throw new RuntimeException(
                'Atur ABSENKITA_DEMO_PASSWORD di file .env (minimal 12 karakter).'
            );
        }

        // 1. Membuat akun Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin.absenkita@example.test'],
            [
                'name' => 'Admin Absenskita',
                'nim_or_nik' => 'ADM-DEMO-001',
                'password' => Hash::make($password),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $admin->role = 'admin';
            $admin->save();
        } elseif (
            $admin->role !== 'admin' ||
            $admin->nim_or_nik !== 'ADM-DEMO-001'
        ) {
            throw new RuntimeException(
                'Email admin demo sudah digunakan akun lain.'
            );
        }

        // 2. Membuat akun Anggota
        User::firstOrCreate(
            ['email' => 'anggota1.absenkita@example.test'],
            [
                'name' => 'Anggota Demo 1',
                'nim_or_nik' => 'AGT-DEMO-001',
                'password' => Hash::make($password),
            ]
        );

        User::firstOrCreate(
            ['email' => 'anggota2.absenkita@example.test'],
            [
                'name' => 'Anggota Demo 2',
                'nim_or_nik' => 'AGT-DEMO-002',
                'password' => Hash::make($password),
            ]
        );

        // 3. Membuat kegiatan organisasi
        $admin->createdEvents()->firstOrCreate(
            [
                'nama_kegiatan' => 'Rapat Anggota Bulanan',
                'tanggal' => '2026-12-15',
            ],
            [
                'jam_mulai' => '09:00:00',
                'jam_selesai' => '11:00:00',
                'status' => 'tutup',
            ]
        );

        $this->command->info(
            'Data demo Absenskita berhasil disiapkan!'
        );
    }
}
