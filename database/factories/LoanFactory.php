<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::factory()->create(1)->id;
            },
            'amount' => rand(1000,10000),
            'term' => rand(2,10),
            'status' => Loan::PENDING
        ];
    }
}
