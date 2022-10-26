<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplication>
 */
class LoanApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(1, 10) * 1000,
            'term' => $this->faker->numberBetween(1, 50),
            'term_period' => 'mo',
            'approved_by' => User::query()->where('type', 'admin')->inRandomOrder()->first()->id,
            'approved_at' => $this->faker->dateTimeBetween('-2 months', '-1 month'),
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ];
    }
}
