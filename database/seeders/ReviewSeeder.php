<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Pastikan kita punya minimal 20 user biasa (role: user)
        $users = [];
        for ($i = 1; $i <= 20; $i++) {
            $users[] = User::updateOrCreate(
                ['username' => 'reviewer_' . $i],
                [
                    'nama' => $faker->name,
                    'email' => "reviewer{$i}@example.com",
                    'no_hp' => $faker->phoneNumber,
                    'gender' => $faker->randomElement(['Pria', 'Wanita']),
                    'usia' => $faker->numberBetween(18, 60),
                    'alamat' => $faker->address,
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        }

        // 2. Ambil semua produk
        $produks = Produk::all();

        if ($produks->isEmpty()) {
            $this->command->info('Tidak ada produk ditemukan. Silakan jalankan ProdukSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Memulai seeding ulasan (17 ulasan per produk)...');

        // 3. Loop tiap produk dan berikan 17 ulasan dari user yang berbeda
        foreach ($produks as $produk) {
            // Acak urutan user agar ulasannya bervariasi tiap produk
            $shuffledUsers = collect($users)->shuffle()->take(17);

            foreach ($shuffledUsers as $user) {
                Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'produk_id' => $produk->id,
                    ],
                    [
                        'rating' => $faker->numberBetween(3, 5), // Berikan rating mayoritas bagus
                        'comment' => $faker->sentence(12),
                        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    ]
                );
            }
        }

        $this->command->info('Seeding ulasan selesai!');
    }
}
