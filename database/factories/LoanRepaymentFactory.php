<?php

namespace Database\Factories;

use App\Models\LoanApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanRepayment>
 */
class LoanRepaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_date' => $this->faker->dateTimeBetween('tomorrow', '1 month')->format('Y-m-d'),
            'week' => $this->faker->numberBetween(1, 100),
            'paid_at' => $this->faker->dateTimeBetween('1 month', '2 months'),
            'amount' => $this->faker->randomFloat(2),
            'interest_amount' => $this->faker->randomFloat(2),
            'interest' => $this->faker->randomFloat(2),
            'total_amount' => $this->faker->randomFloat(2),
            'loan_application_id' => LoanApplication::factory()->create()->id,
        ];
    }
}
