<?php

namespace Tests\Feature\Shared;

use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use App\Services\Allegro\AllegroService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_see_his_offers(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $offersCount = 10;
        $offers = Offer::factory()
            ->count($offersCount)
            ->create([
                'user_id' => $user->id,
            ]);

        $response = $this->getJson(route('offers.index'))
            ->assertOk()
            ->assertJsonCount($offersCount, 'data');

        foreach ($offers as $offer) {
            $response->assertJsonFragment($offer->toArray());
        }
    }

    /** @test */
    public function user_can_see_his_offers_with_actual_price(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $offersCount = 10;
        $offers = Offer::factory()
            ->count($offersCount)
            ->has(PriceHistory::factory()->count(5), 'priceHistories')
            ->create([
                'user_id' => $user->id,
            ]);

        foreach ($offers as $offer) {
            $offer->price_history_actual_id = $offer->priceHistories->first()->id;
            $offer->save();
        }

        $response = $this->getJson(route('offers.index'))
            ->assertOk()
            ->assertJsonCount($offersCount, 'data');

        foreach ($offers as $offer) {
            $response->assertJsonFragment($offer->attributesToArray());
            $response->assertJsonFragment($offer->priceActual->toArray());
        }
    }
}
