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
    public function __construct()
    {
        $this->service = new AllegroService();
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
