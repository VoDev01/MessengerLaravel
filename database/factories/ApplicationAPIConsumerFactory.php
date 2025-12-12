<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicationAPIConsumer>
 */
class ApplicationAPIConsumerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'app_id' => Str::ulid()->toBase32(),
            'app_secret' => Str::random(16),
            'app_url' => 'http://localhost',
            'app_api_endpoint' => 'http://localhost/api/v1'
        ];
    }
}
