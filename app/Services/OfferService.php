<?php

namespace App\Services;

use App\Models\Offer;
use App\Services\Allegro\AllegroOfferCheckerAdapter;
use App\Services\Contract\OfferCheckerInterface;

class OfferService
{
    public $checkerService;

    public function __construct(OfferCheckerInterface $checkerService)
    {
        $this->checkerService = $checkerService;
    }
    public function processOffer(Offer $offer)
    {
        if(!$this->checkerService->canHandle()) {
            // todo: exceptiopn
        }

    }
}
