<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wilayahs = [
            [
                'nama_wilayah' => 'Kecamatan Perak',
                'keterangan' => 'Wilayah pusat kerajinan perak',
            ],
            [
                'nama_wilayah' => 'Kecamatan Jombang',
                'keterangan' => 'Wilayah pusat administrasi',
            ],
            [
                'nama_wilayah' => 'Kecamatan Diwek',
                'keterangan' => 'Wilayah pengembangan UMKM',
            ],
        ];

        foreach ($wilayahs as $wilayah) {
            Wilayah::create($wilayah);
        }
    }
}
