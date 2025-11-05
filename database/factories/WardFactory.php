<?php

namespace Database\Factories;

use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ward>
 */
class WardFactory extends Factory
{
    protected $model = Ward::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ward_name' => fake()->word() . ' Ward',
            'ward_type' => fake()->randomElement(['General', 'ICU', 'Emergency', 'Surgery', 'Pediatric', 'Maternity']),
            'floor' => fake()->numberBetween(1, 10),
            'capacity' => fake()->numberBetween(10, 50),
            'status' => fake()->randomElement(['Active', 'Maintenance', 'Closed']),
        ];
    }
}

