<?php

namespace Tests\Unit;

use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use App\Notifications\OfferPriceChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OfferPriceChangedNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notification_is_send(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $offersCount = 3;
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
        
        foreach ($offers as $offer) {
            Notification::fake();
            $user->notify(new OfferPriceChangedNotification($offer));
            Notification::assertSentTo(
                [$user], OfferPriceChangedNotification::class
            );
        }
    }
}