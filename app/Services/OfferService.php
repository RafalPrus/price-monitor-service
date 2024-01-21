<?php

namespace App\Services;

use App\Models\Offer;

class OfferService
{
    public $adapters;

    public function __construct()
    {
        $this->adapters = [
            new AllegroOfferServiceAdapter(),
        ];
    }
    public function processOffer(Offer $offer)
    {
        foreach ($this->adapters as $adapter) {
            if($adapter->canHandle($offer->url)) {
                $serviceAdapter = $adapter;
            }
        }

        if(empty($serviceAdapter)) {
            throw new \RuntimeException("Unknown Offer Domain, can't process offer");
        }
    }
}
