<?php

namespace App\Services\Wrangler;

use App\Enums\AvailableStore;
use App\Models\Offer;
use App\Services\Contract\OfferCheckerInterface;
use App\Services\UrlService;
use App\Traits\PriceComparisonTrait;

class WranglerOfferCheckerAdapter implements OfferCheckerInterface
{
    use PriceComparisonTrait;
    public const DOMAIN = 'wrangler.com';
    public WranglerService $service;
    public function __construct()
    {
        $this->service = new WranglerService();
    }
    public function getOfferPrice(string $url): float
    {
        return $this->getOfferPrice($url);
    }
    public function canHandle(Offer $offer): bool
    {
        if(!($offer->domain == self::DOMAIN)) {
            return false;
        }

        if(!$this->service->canHandle($offer->url)) {
            return false;
        }

        return true;
    }
}
