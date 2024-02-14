<?php

namespace App\Services;

use App\Events\Offer\OfferPriceChanged;
use App\Models\Offer;
use App\Services\Allegro\AllegroOfferCheckerAdapter;
use App\Services\Contract\OfferCheckerInterface;

class OfferService
{
    public $checkerServices;

    public function __construct()
    {
        $this->checkerServices = config('theapp.checker_services');
    }
    public function processOffer(Offer $offer)
    {

        foreach($this->checkerServices as $adapter) {
            $adapter = (new $adapter);

            if(!$adapter->canHandle($offer)) {
                continue;
            }

            $fetchedPrice = $adapter->getOfferPrice($offer->url);
            break;
        }

        if($fetchedPrice == null) {
            // something went wrong... 
        }

        if ($this->hasPriceChanged($fetchedPrice, $offer->price_current)) {
            $newPrice = $offer->priceHistories->create([
                'price' => $fetchedPrice,
            ]);

            $offer->priceActual()->sync([$newPrice->id]);

            OfferPriceChanged::dispatch($offer);
        }
    }

    public function hasPriceChanged($newPrice, $oldPrice)
    {
        return $newPrice != $oldPrice;
    }
}
