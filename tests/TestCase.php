<?php

namespace Tests;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function createUserLoan($args = [])
    {
        return Loan::factory()->create($args);
    }

    public function authUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user);

        return $user;
    }
}
