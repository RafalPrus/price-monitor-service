<?php

namespace App\Services\Contract;

interface OfferCheckerInterface
{
    public function getOffer(string $url): array;
    public function comparePrice(float $oldPrice, float $newPrice): float;
    public function canHandle(string $domain): bool;
}
