<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class UsahaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $umkmUser = \App\Models\User::where('username', 'umkm_user')->first();

        if ($umkmUser) {
            \App\Models\Usaha::create([
                'kode_usaha' => 'USH-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6)),
                'nama_usaha' => 'Kerajinan Perak Jaya',
                'telp_usaha' => '081234567890',
                'email_usaha' => 'info@perakjaya.com',
                'deskripsi_usaha' => 'Bengkel kerajinan perak tradisional dengan kualitas terbaik.',
                'status_usaha' => 'aktif',
                'user_id' => $umkmUser->id,
                'wilayah_id' => $umkmUser->wilayah_id,
            ]);
        }

        // Bulk UMKM (Usaha)
        \App\Models\Usaha::factory()->count(30)->create();
    }
}
