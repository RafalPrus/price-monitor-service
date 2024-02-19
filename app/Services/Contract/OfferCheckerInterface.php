<?php

namespace App\Services\Contract;

use App\Models\Offer;

interface OfferCheckerInterface
{
    public function getOfferPrice(): float;
    public function canHandle(): bool;
}
