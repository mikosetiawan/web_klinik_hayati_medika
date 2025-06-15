<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nik' => 3600000000000001,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Pasien
        User::create([
            'nik' => 3600000000000002,
            'name' => 'Pasien',
            'email' => 'pasien@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pasien',
        ]);
        

        // Dokter
        User::create([
            'nik' => 3600000000000003,
            'name' => 'Dokter 1',
            'email' => 'dokter1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);
        User::create([
            'nik' => 3600000000000004,
            'name' => 'Dokter 2',
            'email' => 'dokter2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);
        User::create([
            'nik' => 3600000000000005,
            'name' => 'Dokter 3',
            'email' => 'dokter3@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);
        User::create([
            'nik' => 3600000000000006,
            'name' => 'Dokter 4',
            'email' => 'dokter4@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);
        User::create([
            'nik' => 3600000000000007,
            'name' => 'Dokter 6',
            'email' => 'dokter6@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
        ]);
    }
}
