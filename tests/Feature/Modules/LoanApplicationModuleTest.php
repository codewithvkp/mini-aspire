<?php

namespace Tests\Feature\Modules;

use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoanApplicationModuleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_user_can_create_new_loan_application()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        $response = $this->post('/api/loan-applications', [
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('loan_applications', [
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => $user->id,
            'approved_at' => null,
        ]);
    }

    /**
     * @return void
     */
    public function test_user_can_update_loan_application()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('loan_applications', [
            'id' => $application->id,
            'amount' => 5000,
        ]);

        $response = $this->put('/api/loan-applications/' . $application->id, [
            'amount' => 6000,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('loan_applications', [
            'id' => $application->id,
            'amount' => 6000,
        ]);
    }

    /**
     * @return void
     */
    public function test_user_can_delete_loan_application()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('loan_applications', [
            'id' => $application->id,
        ]);

        $response = $this->delete('/api/loan-applications/' . $application->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('loan_applications', [
            'id' => $application->id,
        ]);
    }

    /**
     * @return void
     */
    public function test_admin_can_approve_loan_application()
    {
        $user = User::factory()->create([
            'type' => 'admin'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => User::factory()->create([
                'type' => 'user'
            ])->id,
        ]);

        $response = $this->post('/api/loan-applications/' . $application->id . '/approve');

        $response->assertStatus(200);

        $this->assertNotNull($application->fresh()->approved_at);
    }

    /**
     * @return void
     */
    public function test_user_can_not_approve_loan_application()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        $application = LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => User::factory()->create([
                'type' => 'user'
            ])->id,
        ]);

        $response = $this->post('/api/loan-applications/' . $application->id . '/approve');

        $response->assertStatus(403);

        $this->assertNull($application->fresh()->approved_at);
    }

    /**
     * @return void
     */
    public function test_admin_can_get_all_applications()
    {
        $user = User::factory()->create([
            'type' => 'admin'
        ]);

        $this->actingAs($user);

        foreach (range(1, 5) as $index) {
            LoanApplication::create([
                'amount' => 5000,
                'term' => 5,
                'term_period' => 'mo',
                'user_id' => User::factory()->create([
                    'type' => 'user'
                ])->id,
            ]);
        }

        $response = $this->get('/api/loan-applications');

        $response->assertStatus(200);

        $response->assertJsonCount(5, 'data');
    }

    /**
     * @return void
     */
    public function test_user_can_get_only_his_applications()
    {
        $user = User::factory()->create([
            'type' => 'user'
        ]);

        $this->actingAs($user);

        LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => $user->id,
        ]);

        LoanApplication::create([
            'amount' => 5000,
            'term' => 5,
            'term_period' => 'mo',
            'user_id' => User::factory()->create([
                'type' => 'user'
            ])->id,
        ]);

        $response = $this->get('/api/loan-applications');

        $response->assertStatus(200);

        $response->assertJsonCount(1, 'data');
    }


}
