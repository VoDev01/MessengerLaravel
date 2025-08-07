<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => rand(11111111111, 99999999999),
            'email_verified_at' => now(),
            'password' => Hash::make('1122334455', ['rounds' => 12]),
            'remember_token' => Str::random(10),
            'pfp' => 'https://letters.noticeable.io/' . strtoupper(substr($name, 0, 1)) . rand(0, 19) . '.png'
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
