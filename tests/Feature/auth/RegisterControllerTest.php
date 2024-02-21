<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_register_into_app_and_is_logged_in_after(): void
    {
        $email = 'mail@example.com';
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

    /** @test */
    public function user_can_verify_his_email_after_registration(): void
    {
        $this->markTestSkipped('wymaga poprawy po zmianach w generowanym linku do frontu');
        $email = 'mails@example.com';
        $pass = 'example_pass123';
        $name = 'Joe Doe';
        $credentials = [
            'name' => $name,
            'email' => $email,
            'password' => $pass,
            'password_confirmation' => $pass,
        ];
        
        $response = $this->postJson(route('register'), $credentials);
        $response->assertStatus(201);

        $response = $this->postJson(route('login'), $credentials);
        $response->assertStatus(206);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => $name,
            'email_verified_at' => null,
        ]);
        $user = User::where('email', $email)->first();

        $this->assertNull($user->email_verified_at);
        $verificationService = new VerifyEmail();
        $url = $verificationService->toMail($user)->actionUrl;
        $this->assertNotEmpty($url);
        $verifyResponse = $this->getJson($url);
        $verifyResponse->assertStatus(204);

        $user->refresh();

        $this->assertNotEmpty($user->email_verified_at);
        $this->assertInstanceOf(Carbon::class, $user->email_verified_at);
    }
}
