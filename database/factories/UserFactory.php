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
            'nama' => fake()->name(),
            'nim' => fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => null,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'no_telp' => fake()->phoneNumber(),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'program_studi' => fake()->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'angkatan' => fake()->numberBetween(2018, 2023),
            'alamat' => fake()->address(),
            'level' => 'user',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
