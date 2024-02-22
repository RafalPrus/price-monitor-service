<?php

namespace App\Services\Api;

use App\Models\Offer;
use App\Traits\ThrowExceptionsTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class ScrapeopsService
{
    use ThrowExceptionsTrait;
    public string $name;
    public string $apiProvider;
    public string $apiKey;
    public function __construct()
    {
        $this->name = 'scrapeops';
        $this->apiProvider = config('theapp.api_providers.' . $this->name .'.url');
        $this->apiKey = config('theapp.api_providers.' . $this->name .'.key');
    }

    public function makeRequest(Offer $offer): Response
    {
        $response = Http::timeout(130)->get($this->apiProvider, [
            'api_key' => $this->apiKey,
            'url' => $offer->url,
        ]);

        if(!$response->successful()) {
            if($response->status() == 500) {
                $message = 'Api nie było w stanie pobrać danych (Status code 500) z przesłanego adresu dla oferty o numerze ID: ' . $offer->id;
                Log::error($message);
                $offer->failedRequestBids()->create([
                    'error_message' => $message,
                ]);
                $this->throwApiCantFetchDataException($offer->id);
            }

            $this->throwCantFetchDataException($offer->id, $response->status());
        }

        return $response;
    }
}