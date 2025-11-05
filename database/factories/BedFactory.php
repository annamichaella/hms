<?php

namespace Database\Factories;

use App\Models\Bed;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bed>
 */
class BedFactory extends Factory
{
    protected $model = Bed::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ward_id' => Ward::factory(),
            'bed_number' => fake()->unique()->bothify('?##'),
            'bed_type' => fake()->randomElement(['Standard', 'ICU', 'Private', 'Semi-Private']),
            'status' => fake()->randomElement(['Available', 'Occupied', 'Maintenance', 'Reserved']),
            'patient_id' => null,
            'admission_date' => null,
        ];
    }
}

