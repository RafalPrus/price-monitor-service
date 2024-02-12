<?php

namespace App\Services;

use App\Models\Offer;
use App\Services\Allegro\AllegroOfferCheckerAdapter;
use App\Services\Contract\OfferCheckerInterface;

class OfferService
{
    public $checkerServices;

    public function __construct()
    {
        $this->checkerServices = [
            AllegroOfferCheckerAdapter::class,
            // next adapters...
        ];
    }
    public function processOffer(Offer $offer)
    {
        $domain = UrlService::getDomain($offer->url);

        foreach($this->checkerServices as $adapter) {
            $adapter = (new $adapter);

            if(!$adapter->canHandle($domain)) {
                continue;
            }

            $fetchedPrice = $adapter->getOfferPrice($offer->url);
            break;
        }

        if($fetchedPrice == null) {
            // ??
        }

        $offer->priceHistories->create([
            'price' => $fetchedPrice,
        ]);

    }
}
