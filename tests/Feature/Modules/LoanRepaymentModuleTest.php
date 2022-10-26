<?php

namespace Tests\Feature\Modules;

use App\Actions\LoanApplication\CreateLoanRepaymentRecords;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanRepaymentModuleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_can_create_repayment_records_when_application_approved()
    {
        $user = User::factory()->create([
            'type' => 'admin'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 2000,
            'term' => 2,
            'term_period' => 'mo',
            'user_id' => $user->id,
        ]);

        $response = $this->post('/api/loan-applications/' . $application->id . '/approve');

        $response->assertStatus(200);

        $this->assertEquals($application->repayments()->count(), 8);

        $this->assertDatabaseHas('loan_repayments', [
            "loan_application_id" => $application->id,
            "amount" => "250.0",
            "interest_amount" => "36.25",
            "interest" => "14.5",
            "total_amount" => "286.25",
        ]);

    }

    /**
     * @return void
     */
    public function test_it_get_loan_application_repayment_records()
    {
        $user = User::factory()->create([
            'type' => 'admin'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 2000,
            'term' => 2,
            'term_period' => 'mo',
            'user_id' => $user->id,
            'approved_at' => now(),
        ]);

        CreateLoanRepaymentRecords::run($application);

        $response = $this->get('/api/loan-applications/' . $application->id . '/repayments');

        $response->assertJsonCount(8, 'data');

    }

    /**
     * @return void
     */
    public function test_user_can_repay_loan()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 2000,
            'term' => 2,
            'term_period' => 'mo',
            'user_id' => $user->id,
            'approved_at' => now(),
        ]);

        CreateLoanRepaymentRecords::run($application);

        $repayments = $application->repayments;

        $this->assertNull($repayments->first()->fresh()->paid_at);

        $response = $this->post('/api/loan-repayments/' . $repayments->first()->id . '/repay');

        $response->assertStatus(200);

        $this->assertNotNull($repayments->first()->fresh()->paid_at);
    }


}
