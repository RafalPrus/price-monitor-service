<?php

namespace App\Services\Allegro;

use App\Enums\AvailableStore;
use App\Models\Offer;
use App\Services\Contract\OfferCheckerInterface;
use App\Services\UrlService;
use App\Traits\PriceComparisonTrait;

class AllegroOfferCheckerAdapter implements OfferCheckerInterface
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
    public function canHandle(): bool
    {
        if(!($this->service->offer->domain == self::DOMAIN)) {
            return false;
        }

        if(!$this->service->canHandle()) {
            return false;
        }

        return true;
    }
}
