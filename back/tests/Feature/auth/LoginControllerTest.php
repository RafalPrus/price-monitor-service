<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $user = User::factory()->create($credentials);


        $response = $this->postJson(route('login'), $credentials);
        $response->assertOk();
    }

    /** @test */
    public function login_response_status_is_206_if_try_to_login_when_already_logged(): void
    {
        $email = 'mails@example.com';
        $pass = 'example_pass123';
        $credentials = [
            'email' => $email,
            'password' => $pass,
        ];
        $user = User::factory()->create($credentials);
        $response = $this->postJson(route('login'), $credentials);
        $response->assertOk();

        $response = $this->postJson(route('login'), $credentials);
        $response->assertStatus(206);
    }

    /** @test */
    public function user_can_reset_his_password(): void
    {
        $email = 'mails@example.com';
        $pass = 'example_pass123';
        $credentials = [
            'email' => $email,
            'password' => $pass,
        ];
        $user = User::factory()->create($credentials);
        
        $connection = DB::connection();
        $table = 'password_reset_tokens';
        $key = config('app.key');
        $hasher = Hash::driver();
        $expires = 60;

        $repository = new DatabaseTokenRepository(
            $connection, $hasher, $table, $key, $expires
        );

        $token = $repository->create($user);
        $newPassword = 'total_new_pass123';
        $response = $this->postJson(route('password.update'),
            [
                'token' => $token,
                'email' => $email,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]
        );
        $response->assertOk();

        $logResponse = $this->postJson(route('login'), $credentials);
        $logResponse->assertStatus(422);

        $updatedCredentials = [
            'email' => $email,
            'password' => $newPassword,
        ];

        $logResponse = $this->postJson(route('login'), $updatedCredentials);
        $logResponse->assertOk();
    }
}
