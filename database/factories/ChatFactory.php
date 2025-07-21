<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->word();
        return [
            'name' => $name,
            'logo' => 'https://letters.noticeable.io/' . strtoupper(substr($name, 0, 1)) . rand(0, 19) . '.png',
            'created_at' => Carbon::now()->format('yy-dd-mm h:i:s')
        ];
    }
}
