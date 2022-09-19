<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test if a User can Register in App.
     *
     * @return void
     */
    public function test_a_user_can_register()
    {
        $this->postJson(route('auth.register'),[
            'name' => 'Vikas Kardam',
            'email' => 'vkardam24@gmail.com',
            'password' => 'password',
            'role_id' => 0
        ])->assertOk();
    }
}
