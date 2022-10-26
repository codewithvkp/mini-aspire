<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthModuleTest extends TestCase
{
    /**
     * @return void
     */
    public function test_it_can_register_new_user_and_returns_access_token(): void
    {
        $this->createPersonalClient();

        $response = $this->post('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => '123456789',
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com'
        ]);

        $this->assertNotNull($response->json('data.access_token'));

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@gmail.com',
            'type' => 'user',
        ]);
    }

    /**
     * @return void
     */
    public function test_it_can_login_and_returns_access_token()
    {
        $this->createPersonalClient();

        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $this->assertNotNull($response->json('data.access_token'));
    }

    /**
     * @return void
     */
    public function test_it_can_throw_invalid_credentials_error()
    {
        $this->createPersonalClient();

        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }
}
