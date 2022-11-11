<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->words(rand(1,3),true),
            'description' => fake()->paragraphs(rand(2,4),true),
            'obtained' => fake()->dateTimeThisDecade()->format('Y-m-d'),

        ];
    }
}
