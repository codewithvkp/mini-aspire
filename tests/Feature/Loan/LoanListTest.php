<?php

namespace Tests\Feature\Loan;

use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanListTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }

    public function test_if_user_create_loan()
    {
        $this->postJson(route('loan.store'),[
            'user_id' => $this->authUser()->id,
            'amount' => 10000,
            'term' => 3,
            'status' => Loan::PENDING
        ])->assertOk();

    }

    public function test_if_user_has_loan()
    {
        $response = $this->getJson(route('loan.index'));
        $this->assertEquals(3,count($response->json()));
    }
}
