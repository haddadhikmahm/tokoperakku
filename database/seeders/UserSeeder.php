<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Admin Utama
        User::updateOrCreate(
            ['username' => 'admin_utama'],
            [
                'nama' => 'Rahmad Hadi',
                'email' => 'admin@teko.com',
                'no_hp' => '086628473301',
                'gender' => 'Pria',
                'usia' => 51,
                'alamat' => 'Basen KG III / 283 RT 15 rw 04',
                'password' => Hash::make('password'),
                'role' => 'admin_utama',
            ]
        );

        // Admin Wilayah (Linked to Kecamatan Perak)
        User::updateOrCreate(
            ['username' => 'admin_wilayah'],
            [
                'nama' => 'Budi Wilayah',
                'email' => 'admin_perak@teko.com',
                'no_hp' => '081222333444',
                'gender' => 'Pria',
                'usia' => 35,
                'alamat' => 'Jl. Mondorakan No. 12, Kotagede',
                'password' => Hash::make('password'),
                'role' => 'admin_wilayah',
                'wilayah_id' => 1,
            ]
        );

        // UMKM User (Linked to Kecamatan Perak)
        User::updateOrCreate(
            ['username' => 'umkm_user'],
            [
                'nama' => 'Situmorang Silver',
                'email' => 'umkm@teko.com',
                'no_hp' => '081333444555',
                'gender' => 'Pria',
                'usia' => 42,
                'alamat' => 'Purbayan KG III, Kotagede',
                'password' => Hash::make('password'),
                'role' => 'umkm',
                'wilayah_id' => 1,
            ]
        );
        User::updateOrCreate(
            ['username' => 'umkm_user2'],
            [
                'nama' => 'Situmorang Silver2',
                'email' => 'umkm2@teko.com',
                'no_hp' => '0813334445552',
                'gender' => 'Pria',
                'usia' => 42,
                'alamat' => 'Purbayan KG III, Kotagede2',
                'password' => Hash::make('password'),
                'role' => 'umkm',
                'wilayah_id' => 1,
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['username' => 'regular_user'],
            [
                'nama' => 'Andi Pembeli',
                'email' => 'user@teko.com',
                'no_hp' => '085666777888',
                'gender' => 'Pria',
                'usia' => 28,
                'alamat' => 'Jl. Gedongkuning No. 45, Yogyakarta',
                'password' => Hash::make('password'),
                'role' => 'user',
                'wilayah_id' => 2,
            ]
        );
        User::updateOrCreate(
            ['username' => 'regular_user2'],
            [
                'nama' => 'Andi Pembeli2',
                'email' => 'user2@teko.com',
                'no_hp' => '085666777882',
                'gender' => 'Pria',
                'usia' => 28,
                'alamat' => 'Jl. Gedongkuning No. 45, Yogyakarta2',
                'password' => Hash::make('password'),
                'role' => 'user',
                'wilayah_id' => 2,
            ]
        );

        // Additional Admins for testing pagination
        for ($i = 1; $i <= 15; $i++) {
            User::updateOrCreate(
                ['username' => 'admin_test_' . $i],
                [
                    'nama' => 'Admin Tester ' . $i,
                    'email' => 'admin' . $i . '@teko.com',
                    'no_hp' => '0812345678' . $i,
                    'gender' => $i % 2 == 0 ? 'Wanita' : 'Pria',
                    'usia' => 25 + $i,
                    'alamat' => 'Alamat Admin Test No. ' . $i,
                    'password' => Hash::make('password'),
                    'role' => $i % 3 == 0 ? 'admin_utama' : 'admin_wilayah',
                ]
            );
        }
    }
}
