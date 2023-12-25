<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_login_into_app(): void
    {
        $email = 'mails@example.com';
        $pass = 'example_pass123';
        $credentials = [
            'email' => $email,
            'password' => $pass,
        ];
        $user = User::factory([])->create($credentials);


        $response = $this->postJson(route('login'), $credentials);
        $response->assertOk();
    }
}
