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
                'email' => 'admin_perak@teko.com',
                'password' => Hash::make('password'),
                'role' => 'admin_wilayah',
                'wilayah_id' => 1,
            ]
        );

        // UMKM User (Linked to Kecamatan Perak)
        User::updateOrCreate(
            ['username' => 'umkm_user'],
            [
                'email' => 'umkm@teko.com',
                'password' => Hash::make('password'),
                'role' => 'umkm',
                'wilayah_id' => 1,
            ]
        );

        // Regular User
        User::updateOrCreate(
            ['username' => 'regular_user'],
            [
                'email' => 'user@teko.com',
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
