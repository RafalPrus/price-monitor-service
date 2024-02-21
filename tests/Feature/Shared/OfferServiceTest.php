<?php

namespace Tests\Feature\Shared;

use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OfferServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function offer_service_is_working_and_select_correct_adapter(): void
    {
        // $offer = Offer::factory()->create([
        //     'url' => $this->getUrl(),
        // ]);

        // Http::fake([
        //     '*' => Http::response($this->excerptBodyOffer(), 200),
        // ]);

        //TODO: Dokonczyć
        
    }
}