<?php

namespace App\Services\Allegro;

use App\Enums\AvailableStore;
use App\Services\Contract\OfferCheckerInterface;
use App\Traits\PriceComparisonTrait;

class AllegroOfferCheckerAdapter implements OfferCheckerInterface
{
    use PriceComparisonTrait;
    public function getOfferPrice(string $url): array
    {
        // TODO: Implement getOffer() method.
        return [];
    }
    public function canHandle(string $domain): bool
    {
        return $domain == AvailableStore::ALLEGRO->value;
    }
}
