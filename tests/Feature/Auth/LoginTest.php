<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test if a User try Login without Registration.
     *
     * @return void
     */
    public function test_if_a_user_login_without_registration()
    {
        $this->postJson(route('auth.login'),[
            'email' => 'test@test.com',
            'password' => 'WrongPassword',
        ])->assertUnauthorized();
    }

    /**
     * Test if a User Enter Wrong Password.
     *
     * @return void
     */
    public function test_if_a_user_enter_wrong_password()
    {
        $this->postJson(route('auth.login'),[
            'email' => 'vkardam24@gmail.com',
            'password' => 'WrongPassword',
        ])->assertUnauthorized();
    }

    /**
     * Test if a User Can Login.
     *
     * @return void
     */
    public function test_a_user_can_login()
    {
        $response = $this->postJson(route('auth.login'),[
           'email' => 'vkardam24@gmail.com',
           'password' => 'password',
        ])->assertOk();

        $this->assertArrayHasKey('token',$response->json());
    }
}
