<?php

namespace App\Services;

abstract class AbstractOfferCheckerAdapter
{
    public function canHandleDomain(): bool
    {
        if(!($this->service->offer->domain == static::DOMAIN)) {
            return false;
        }

        return true;
    }
}