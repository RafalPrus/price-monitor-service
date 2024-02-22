<?php

namespace App\Services\Allegro;

use App\Enums\AvailableStore;
use App\Models\Offer;
use App\Services\AbstractOfferCheckerAdapter;
use App\Services\Contract\OfferCheckerInterface;
use App\Services\UrlService;
use App\Traits\PriceComparisonTrait;

class AllegroOfferCheckerAdapter extends AbstractOfferCheckerAdapter implements OfferCheckerInterface
{
    use PriceComparisonTrait;
    public const DOMAIN = 'allegro.pl';
    public AllegroService $service;
    public function __construct($offer)
    {
        $this->service = new AllegroService($offer);
    }
    public function getOfferPrice(): float
    {
        return $this->service->getOfferPrice();
    }

    public function getOfferBody(): bool
    {
        if(!$this->service->getOfferBody()) {
            return false;
        }

        return true;
    }
}
