<?php

namespace Database\Factories;

use App\Models\Billing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billing>
 */
class BillingFactory extends Factory
{
    protected $model = Billing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_name' => fake()->name(),
            'doctor_name' => fake()->optional()->name(),
            'service' => fake()->words(3, true),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'status' => fake()->randomElement(['pending', 'partial', 'paid', 'overdue']),
            'billing_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'due_date' => fake()->optional(0.7)->dateTimeBetween('now', '+1 month')?->format('Y-m-d'),
            'payment_method' => fake()->optional()->randomElement(['cash', 'card', 'insurance']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

