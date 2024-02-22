<?php

namespace Tests\Feature\Shared;

use App\Models\Offer;
use App\Services\Allegro\AllegroService;
use App\Services\Wrangler\WranglerOfferCheckerAdapter;
use App\Services\Wrangler\WranglerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WranglerServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function wrangler_service_is_returning_price_from_url(): void
    {
        $offer = Offer::factory()->create([
            'url' => $this->getUrl(),
        ]);

        Http::fake([
            '*' => Http::response($this->excerptBodyOffer(), 200),
        ]);

        $service = new WranglerService($offer);
        $service->getOfferBody();
        $fetchedPrice = $service->getOfferPrice();
        
        $this->assertSame(69.5, $fetchedPrice);
    }

    /** @test */
    public function wrangler_service_adapter_is_working_and_returns_price_from_url(): void
    {
        $offer = Offer::factory()->create([
            'url' => $this->getUrl(),
        ]);

        Http::fake([
            '*' => Http::response($this->excerptBodyOffer(), 200),
        ]);

        $adapter = new WranglerOfferCheckerAdapter($offer);

        $canHandleDomain = $adapter->canHandleDomain();
        if ($canHandleDomain) {
            $adapter->getOfferBody();
            $fetchedPrice = $adapter->getOfferPrice();
        }

        $this->assertSame(true, $canHandleDomain);
        $this->assertSame(69.5, $fetchedPrice);
    }
    public function getUrl(): string
    {
        return "https://eu.wrangler.com/pl-pl/shop/outlet/mezczyzni/wool-cap-in-dahlia-112344018.html?merchCategory=c100000";
    }

    public function excerptBodyOffer(): string
    {
        return '<div class="pdp-detail prices-range-section">
                    <!-- Product Name -->
                    <div class="row hidden-on-mobile">
                        <div class="col-12">
                            <h1 class="product-name">Wool Cap in Dahlia</h1>
                        </div>
                    </div>

                    <div class="row hidden-on-mobile">
                        <div class="col-12 eye-brow-section">         
                        </div>
                    </div>

                    <div class="row hidden-on-mobile">
                        <div class="col-12">
                            <!-- Prices -->
                            <div class="prices">            
        <div class="price d-inline-block">
        <span class="is-final-price-exist">
        <del class="">
        <span class="strike-through list">
            <span class="value" content="139.00">
                <span class="sr-only">
                    Cena obniżona z
                </span>
                
        zł139,00
                <span class="sr-only">
                    na
                </span>
            </span>
        </span>
        </del>
            <del class="item-on-sale d-none"></del>
            <span class="sales display-sales-price">
                    <span class="value" content="69.50">     
        zł69,50
                    </span>
            </span>
        </span>';
    }
}
