<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'no_hp' => fake()->phoneNumber(),
            'gender' => fake()->randomElement(['Pria', 'Wanita']),
            'usia' => fake()->numberBetween(18, 60),
            'alamat' => fake()->address(),
            'password' => Hash::make('password'),
            'role' => 'user',
            'wilayah_id' => fake()->numberBetween(1, 3),
        ];
    }

    /**
     * Role states
     */
    public function adminUtama(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin_utama',
        ]);
    }

    public function adminWilayah(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin_wilayah',
        ]);
    }

    public function umkm(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'umkm',
        ]);
    }

    public function regularUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
        ]);
    }
}
