<?php

namespace App\Services;

use App\Enums\AvailableStore;
use App\Services\Contract\OfferCheckerInterface;
use App\Traits\PriceComparisonTrait;

class AllegroOfferServiceAdapter implements OfferCheckerInterface
{
    use PriceComparisonTrait;
    public function getOffer(string $url): array
    {
        // TODO: Implement getOffer() method.
    }
    public function canHandle(string $domain): bool
    {
        return $domain == AvailableStore::ALLEGRO->value;
    }
}
