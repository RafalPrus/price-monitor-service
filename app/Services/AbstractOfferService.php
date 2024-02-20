<?php

namespace App\Services;

use App\Exceptions\ApiCantFetchDataException;
use App\Exceptions\InvalidBodyResponseException;

abstract class AbstractOfferService
{
    public function throwCantFetchDataException(int $offerId, $responseStatus): InvalidBodyResponseException
    {
        throw new InvalidBodyResponseException('Api nie było w stanie pobrać danych z oferty. Id oferty: ' . $offerId . ' Otrzymany status: ' . $responseStatus);
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