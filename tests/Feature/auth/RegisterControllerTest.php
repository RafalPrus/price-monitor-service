<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_register_into_app_and_is_logged_in_after(): void
    {
        $email = 'mails@example.com';
        $pass = 'example_pass123';
        $credentials = [
            'email' => $email,
            'password' => $pass,
        ];

        $newUser = User::factory()->make($credentials);
        $newUser = $newUser->toArray();
        $newUser['password'] = $pass;
        $newUser['password_confirmation'] = $pass;
        $response = $this->postJson(route('register'), $newUser);
        $response->assertStatus(201)
            ->assertJsonFragment(['name' => $newUser['name']])
            ->assertJsonFragment(['email' => $newUser['email']]);

    }
}
