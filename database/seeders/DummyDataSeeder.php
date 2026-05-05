<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriProduk;
use App\Models\JenisUsaha;
use App\Models\Pengerajin;
use App\Models\Usaha;
use App\Models\Produk;
use App\Models\Pelaporan;
use App\Models\FotoProduk;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Kategori Produk
        $categories = ['Cincin', 'Kalung', 'Anting', 'Gelang', 'Bros', 'Miniatur', 'Peralatan Makan', 'Hiasan Dinding', 'Gantungan Kunci', 'Kerajinan Filigree'];
        foreach ($categories as $cat) {
            KategoriProduk::updateOrCreate(
                ['slug' => Str::slug($cat)],
                [
                    'nama_kategori_produk' => $cat,
                    'kode_kategori_produk' => 'KAT-' . strtoupper(Str::random(4)),
                ]
            );
        }

        // 2. Jenis Usaha
        $jenisUsahas = ['Produksi', 'Reseller', 'Eksportir', 'Toko Ritel', 'Bengkel Perak'];
        foreach ($jenisUsahas as $jenis) {
            JenisUsaha::updateOrCreate(
                ['nama_jenis_usaha' => $jenis],
                ['kode_jenis_usaha' => 'JNS-' . strtoupper(Str::random(4))]
            );
        }

        // 3. Wilayah (Ensure at least some exist)
        if (Wilayah::count() < 2) {
            Wilayah::updateOrCreate(['nama_wilayah' => 'Kotagede Selatan'], ['keterangan' => 'Pusat pengrajin sepuh']);
            Wilayah::updateOrCreate(['nama_wilayah' => 'Kotagede Utara'], ['keterangan' => 'Pusat galeri perak']);
        }

        $wilayahIds = Wilayah::pluck('id')->toArray();
        $userUmkmIds = User::where('role', 'umkm')->pluck('id')->toArray();

        if (empty($userUmkmIds)) {
            $this->command->warn('No UMKM users found. Please run UserSeeder first.');
            return;
        }

        // 4. Pengerajin (10 items)
        for ($i = 1; $i <= 10; $i++) {
            Pengerajin::create([
                'kode_pengerajin' => 'PNG-' . strtoupper(Str::random(6)),
                'nama_pengerajin' => $faker->name,
                'jk_pengerajin' => $faker->randomElement(['P', 'W']),
                'usia_pengerajin' => $faker->numberBetween(20, 70),
                'alamat_pengerajin' => $faker->address,
                'telp_pengerajin' => $faker->phoneNumber,
                'email_pengerajin' => $faker->email,
            ]);
        }

        // 5. Usaha (10 items)
        $jenisIds = JenisUsaha::pluck('id')->toArray();
        for ($i = 1; $i <= 10; $i++) {
            $usaha = Usaha::create([
                'kode_usaha' => 'USH-' . strtoupper(Str::random(6)),
                'nama_usaha' => $faker->company . ' Silver',
                'user_id' => $faker->randomElement($userUmkmIds),
                'wilayah_id' => $faker->randomElement($wilayahIds),
                'telp_usaha' => $faker->phoneNumber,
                'email_usaha' => $faker->companyEmail,
                'deskripsi_usaha' => $faker->sentence(10),
                'status_usaha' => 'aktif',
            ]);
            $usaha->jenisUsahas()->sync($faker->randomElements($jenisIds, rand(1, 2)));
        }

        // 6. Produk (20 items)
        $usahaIds = Usaha::pluck('id')->toArray();
        $catIds = KategoriProduk::pluck('id')->toArray();
        for ($i = 1; $i <= 20; $i++) {
            $produk = Produk::create([
                'kode_produk' => 'PRD-' . strtoupper(Str::random(6)),
                'kategori_produk_id' => $faker->randomElement($catIds),
                'nama_produk' => $faker->words(3, true),
                'deskripsi' => $faker->paragraph,
                'harga' => $faker->numberBetween(50000, 5000000),
                'stok' => $faker->numberBetween(1, 100),
            ]);
            // Note: slug is handled by boot() in Produk model
            $produk->usaha()->sync($faker->randomElement($usahaIds));

            // 6b. Foto Produk (1-3 photos per product)
            $fotoDummy = ['men-01.jpg', 'women-01.jpg', 'kid-01.jpg', 'filigeri.jpg', 'souvenir.jpg', 'aksesoris-manten.jpg'];
            for ($j = 0; $j < rand(1, 2); $j++) {
                FotoProduk::create([
                    'produk_id' => $produk->id,
                    'file_foto_produk' => 'produk/' . $faker->randomElement($fotoDummy),
                ]);
            }
        }

        // 7. Pelaporan (20 items)
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        for ($i = 1; $i <= 20; $i++) {
            Pelaporan::create([
                'kode_laporan' => 'LAP-' . strtoupper(Str::random(6)),
                'usaha_id' => $faker->randomElement($usahaIds),
                'bulan' => $faker->randomElement($months),
                'tahun' => 2024,
                'omset' => $faker->numberBetween(5000000, 50000000),
                'deskripsi' => 'Laporan penjualan rutin bulan ini.',
            ]);
        }

        // 8. Favorit untuk regular_user (agar halaman favorit tidak kosong)
        $regularUser = User::where('username', 'regular_user')->first();
        if ($regularUser) {
            $randomProduks = Produk::inRandomOrder()->take(3)->pluck('id');
            $regularUser->favoritProduks()->sync($randomProduks);
        }
    }
}
