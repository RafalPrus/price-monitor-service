<?php

namespace App\Services;

use App\Events\Offer\OfferPriceChanged;
use App\Exceptions\InvalidBodyResponseException;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;

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
            $adapter = (new $adapter($offer));
            
            try {
                if(!$adapter->canHandle($offer)) {
                    continue;
                }

                $fetchedPrice = $adapter->getOfferPrice($offer->url);
            } catch (InvalidBodyResponseException $e) {
                Log::error($e->getMessage());
                $offer->failedRequestBids()->create([
                    'error_message' => $e->getMessage(),
                ]);

                $fetchedPrice = null;
                break;
            }
            
            break;
        }

        if($fetchedPrice == null) {
            return;
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
