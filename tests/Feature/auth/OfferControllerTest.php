<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_add_new_offer(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $offer = [
            'url' => 'https://allegro.pl/',
            'name' => 'New offer',
            'is_active' => 1
        ];

        $response = $this->postJson(route('offers.store'), $offer)
                ->assertCreated()
                ->assertJsonFragment(['url' => 'https://allegro.pl/']);

        $this->assertDatabaseCount('offers', 1);
        $this->assertDatabaseHas('offers', [
            'url' => 'https://allegro.pl/',
            'name' => 'New offer',
            'is_active' => 1
        ]);
    }
}
