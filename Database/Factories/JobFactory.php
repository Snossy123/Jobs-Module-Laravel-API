<?php

namespace Modules\Jobs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Jobs\App\Models\Job::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'city' => fake()->city(),
            'country' => fake()->country(),
            'company_industry' => fake()->text(),
            'company_type' => "Employer (Private Sector)",
            'job_role' => fake()->text(),
            'employment_type' => fake()->text(),
            'working_hours' => fake()->numberBetween(4, 12),
            'salary' => fake()->text(),
            'vacancies' => fake()->text(),
            'years_experience' => fake()->text(),
            'description' => fake()->text(),
            'key_responsibilities' => fake()->text(),
            'qualifications' => fake()->text(),
        ];
    }
}

