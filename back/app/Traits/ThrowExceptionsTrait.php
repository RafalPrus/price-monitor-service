<?php

namespace App\Traits;

use App\Exceptions\ApiCantFetchDataException;
use App\Exceptions\InvalidBodyResponseException;

trait ThrowExceptionsTrait
{
    public function throwCantFetchDataException(int $offerId, $responseStatus): InvalidBodyResponseException
    {
        throw new InvalidBodyResponseException('Błąd po stronie API. Id oferty: ' . $offerId . ' Otrzymany status: ' . $responseStatus);
    }

    public function throwCantProccesOfferPriceException(int $offerId, string $body): InvalidBodyResponseException
    {
        throw new InvalidBodyResponseException('Serwis nie był w stanie przetworzyć ceny z otrzymanej odpowiedzi. Id oferty: ' . $offerId . ' Otrzymane body: ' . $body);
    }

    public function throwApiCantFetchDataException($message)
    {
        throw new ApiCantFetchDataException($message);
    }
}