<?php

namespace App\Services;

use App\Events\Offer\OfferPriceChanged;
use App\Exceptions\InvalidBodyResponseException;
use App\Models\Offer;
use Illuminate\Support\Facades\Log;

class OfferService
{
    public $checkerServices;
    public bool $success = true;

    public function __construct()
    {
        $this->checkerServices = config('theapp.checker_services');
    }
    public function processOffer(Offer $offer)
    {

        foreach($this->checkerServices as $adapter) {
            $adapter = (new $adapter($offer));
            
            try {
                if(!$adapter->canHandleDomain($offer)) {
                    continue;
                }

                $adapter->getOfferBody();
                $fetchedPrice = $adapter->getOfferPrice();

            } catch (InvalidBodyResponseException $e) {
                Log::error($e->getMessage());
                $offer->failedRequestBids()->create([
                    'error_message' => $e->getMessage(),
                ]);

                $this->success = false;
                break;
            }
            
            break;
        }

        if($fetchedPrice == null) {
            return;
        }

        if (empty(($offer->price_current)) | ($this->hasPriceChanged($fetchedPrice, $offer->price_current))) {
            $newPrice = $offer->priceHistories()->create([
                'price' => $fetchedPrice,
            ]);

            $offer->update([
                'price_history_actual_id' => $newPrice->id,
            ]);

            $offer->refresh();

            OfferPriceChanged::dispatch($offer);
        }
    }

    public function hasPriceChanged($newPrice, $oldPrice)
    {
        return $newPrice != $oldPrice;
    }
}
 