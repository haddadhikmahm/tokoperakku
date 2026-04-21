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
        User::create([
            'username' => 'admin_utama',
            'email' => 'admin@teko.com',
            'password' => Hash::make('password'),
            'role' => 'admin_utama',
        ]);

        // Admin Wilayah (Linked to Kecamatan Perak)
        User::create([
            'username' => 'admin_wilayah',
            'email' => 'admin_perak@teko.com',
            'password' => Hash::make('password'),
            'role' => 'admin_wilayah',
            'wilayah_id' => 1,
        ]);

        // UMKM User (Linked to Kecamatan Perak)
        User::create([
            'username' => 'umkm_user',
            'email' => 'umkm@teko.com',
            'password' => Hash::make('password'),
            'role' => 'umkm',
            'wilayah_id' => 1,
        ]);

        // Regular User
        User::create([
            'username' => 'regular_user',
            'email' => 'user@teko.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'wilayah_id' => 2,
        ]);
    }
}
