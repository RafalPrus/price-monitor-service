<?php

namespace App\Services\Contract;

use App\Models\Offer;

interface OfferCheckerInterface
{
    public function getOfferPrice(string $url): float;
    public function canHandle(Offer $offer): bool;
}
