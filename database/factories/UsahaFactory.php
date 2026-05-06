<?php

namespace Database\Factories;

use App\Models\Usaha;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usaha>
 */
class UsahaFactory extends Factory
{
    protected $model = Usaha::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_usaha' => 'USH-' . Str::upper(Str::random(6)),
            'nama_usaha' => fake()->company() . ' Silver',
            'telp_usaha' => fake()->phoneNumber(),
            'email_usaha' => fake()->unique()->companyEmail(),
            'deskripsi_usaha' => fake()->paragraph(),
            'status_usaha' => 'aktif',
            'link_gmap_usaha' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
            'link_website_usaha' => fake()->url(),
            'link_wa_usaha' => 'https://wa.me/' . fake()->numerify('############'),
            'link_tokopedia_usaha' => 'https://tokopedia.com/' . fake()->slug(),
            'link_shopee_usaha' => 'https://shopee.co.id/' . fake()->slug(),
            'link_instagram_usaha' => 'https://instagram.com/' . fake()->userName(),
            'spesialisasi_usaha' => fake()->randomElement(['Cincin', 'Kalung', 'Anting', 'Pajangan', 'Custom']),
            'user_id' => User::factory()->umkm(),
            'wilayah_id' => Wilayah::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
