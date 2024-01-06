<?php

namespace Tests\Feature\auth;

use App\Models\Offer;
use App\Models\PriceHistory;
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

    /** @test */
    public function user_cannot_add_new_offer_with_not_available_domain(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $offer = [
            'url' => 'https://allegro-wrong.pl/',
            'name' => 'New offer',
            'is_active' => 1
        ];

        $response = $this->postJson(route('offers.store'), $offer)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('url');

        $this->assertDatabaseMissing('offers', $offer);
    }

    /** @test */
    public function user_can_update_his_offer(): void
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

        $offerId = $response->json('data.id');

        $updateResposne = $this->putJson(route('offers.update', $offerId), [
            'is_active' => 0
        ])
            ->assertStatus(200);

        $this->assertDatabaseCount('offers', 1);
        $this->assertDatabaseHas('offers', [
            'url' => 'https://allegro.pl/',
            'name' => 'New offer',
            'is_active' => 0
        ]);
    }

    /** @test */
    public function user_cannot_update_others_users_offers(): void
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

        $offerId = $response->json('data.id');

        $user2 = User::factory()->create();
        $this->actingAs($user2);

        $updateResposne = $this->putJson(route('offers.update', $offerId), [
            'is_active' => 0
        ])
            ->assertStatus(404);

        $this->assertDatabaseHas('offers', $offer);
    }

    /** @test */
    public function user_can_delete_his_offer(): void
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

        $offerId = $response->json('data.id');

        $updateResposne = $this->deleteJson(route('offers.destroy', $offerId))
            ->assertStatus(204);


        $this->assertDatabaseCount('offers', 0);
    }

    /** @test */
    public function user_cannot_delete_other_user_offer(): void
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

        $offerId = $response->json('data.id');

        $user2 = User::factory()->create();
        $this->actingAs($user2);

        $updateResposne = $this->deleteJson(route('offers.destroy', $offerId))
            ->assertStatus(404);


        $this->assertDatabaseCount('offers', 1);
    }

    /** @test */
    public function user_can_see_list_of_his_offers_only(): void
    {
        $user = User::factory()->create();
        Offer::factory(5)->create([
            'user_id' => $user->id,
        ]);

        $user2 = User::factory()->create();
        Offer::factory(5)->create([
            'user_id' => $user2->id,
        ]);

        $this->actingAs($user);

        $response = $this->getJson(route('offers.index'))
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(['user_id' => $user->id])
            ->assertJsonMissing(['user_id' => $user2->id]);

        $this->assertDatabaseCount('offers', 10);
    }

    /** @test */
    public function user_can_see_single_offer_and_it_has_price_histories(): void
    {
        $user = User::factory()->create();
        $offer = Offer::factory()->create([
            'user_id' => $user->id,
        ]);

        $priceHistories = PriceHistory::factory(5)->create(['offer_id' => $offer->id])->toArray();

        $this->actingAs($user);

        $response = $this->getJson(route('offers.show', $offer))
            ->assertOk()
            ->assertJsonFragment([$offer->url])
            ->assertJsonFragment([$offer->name]);

        foreach ($priceHistories as $priceHistory) {
            $response->assertJsonFragment($priceHistory);
        }
    }
}
