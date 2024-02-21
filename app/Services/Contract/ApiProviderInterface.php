<?php

namespace App\Services\Contract;

use App\Models\Offer;

interface ApiProviderInterface
{
    public function makeRequest(): float;
}
