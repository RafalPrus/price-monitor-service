<?php

namespace Tests\Feature\Shared;

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
     public function user_cannot_update_offer_url(): void
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
             'is_active' => 0,
             'name' => 'Complete New Name',
             'url' => 'https://amazon.pl/'
         ])
             ->assertStatus(200);
 
         $this->assertDatabaseCount('offers', 1);
         $this->assertDatabaseHas('offers', [
             'url' => 'https://allegro.pl/',
             'name' => 'Complete New Name',
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

    /** @test */
    public function user_can_sort_his_offers_by_actual_price(): void
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

        $prices = [];
        foreach($offers as $index => $offer) {
            $prices[$index] = ['offer_id' => $offer->id, 'actual_price' => $offer->price_current];
        }
        usort($prices, function($a, $b) {
            return $a['actual_price'] <=> $b['actual_price'];
        });

        $response = $this->getJson(route('offers.index', ['sort' => 'price-actual']))
            ->assertOk()
            ->assertJsonCount($offersCount, 'data');

        $r = $response->json('data');

        foreach ($prices as $index => $price) {
            $this->assertEquals($r[$index]['id'], $price['offer_id']);
            $this->assertEquals($r[$index]['price_actual']['price'], $price['actual_price']);
        }
    }

    /** @test */
    public function user_can_filter_his_offers_by_domain(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $offersCount = 10;
        $offers = Offer::factory()
            ->count($offersCount)
            ->create([
                'user_id' => $user->id,
            ]);
        
        $domains = [];
        foreach ($offers as $index => $offer) {
            if (empty($domains[$offer->domain])) {
                $domains[$offer->domain] = 1;
            } else {
                $domains[$offer->domain] += 1;
            }

            if ($index == 9) {
                Offer::factory()
                    ->count(3)
                    ->create([
                        'user_id' => $user->id,
                        'url' => $offer->url,
                    ]);
                
                $domains[$offer->domain] += 3;
            }
        };

        foreach ($domains as $domain => $count) {
            $response = $this->getJson(route('offers.index', ['filter' => ['domain' => $domain]]))
                ->assertOk()
                ->assertJsonCount($count, 'data')
                ->assertJsonFragment(['domain' => $domain]);
        }

        $firstFilter = [array_key_first($domains), $domains[array_key_first($domains)]];
        $secondFilter = [array_key_last($domains), $domains[array_key_last($domains)]];
        $expectedOffersCount = $firstFilter[1] + $secondFilter[1];
        $response = $this->getJson(route('offers.index', ['filter' => ['domain' => [$firstFilter[0], $secondFilter[0]]]]))
                ->assertOk()
                ->assertJsonCount($expectedOffersCount, 'data');
    }
}
