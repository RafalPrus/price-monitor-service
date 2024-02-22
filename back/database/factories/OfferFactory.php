<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\UrlService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'url' => fake()->url(),
            'domain' => function(array $attributes) {
                return UrlService::getDomain($attributes['url']);
            },
            'is_active' => fake()->numberBetween(0, 1),
        ];
    }
}
