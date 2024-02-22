<?php

namespace App\Traits;

trait PriceComparisonTrait
{
    public function comparePrice(float $oldPrice, float $newPrice): float
    {
        return $newPrice < $oldPrice;
    }
}
