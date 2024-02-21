<?php

namespace App\Services\Wrangler;

use App\Enums\AvailableStore;
use App\Models\Offer;
use App\Services\AbstractOfferCheckerAdapter;
use App\Services\Contract\OfferCheckerInterface;
use App\Services\UrlService;
use App\Traits\PriceComparisonTrait;

class WranglerOfferCheckerAdapter extends AbstractOfferCheckerAdapter implements OfferCheckerInterface
{
    use PriceComparisonTrait;
    public const DOMAIN = 'eu.wrangler.com';
    public WranglerService $service;
    public function __construct(Offer $offer)
    {
        $this->service = new WranglerService($offer);
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
