<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Desa>
 */
class DesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Desa ' . $this->faker->city(),
            'code' => $this->faker->unique()->numerify('####'),
            'kecamatan' => 'Kecamatan ' . $this->faker->city(),
            'kabupaten' => 'Kabupaten ' . $this->faker->city(),
            'provinsi' => $this->faker->randomElement([
                'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Sumatera Utara',
                'Sumatera Barat', 'Kalimantan Timur', 'Sulawesi Selatan'
            ]),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'status' => 'active',
            'subscription_data' => [
                'package' => 'basic',
                'features' => ['citizen_management', 'letter_service', 'news']
            ],
            'subscription_expires_at' => now()->addYear(),
        ];
    }
}