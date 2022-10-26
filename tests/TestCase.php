<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function createPersonalClient(): self | static
    {
        Artisan::call('passport:client', [
            '--personal' => true,
            '--name' => 'Temp Personal Client',
            '--no-interaction' => true,
        ]);

        return $this;
    }

    public function actingAs(Authenticatable $user, $guard = null): self | static
    {
        Passport::actingAs($user);

        return $this;
    }
}
