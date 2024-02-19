<?php

namespace App\Services\Wrangler;

use App\Models\Offer;
use App\Services\AbstractOfferService;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class WranglerService extends AbstractOfferService
{
    public string $className;
    public string $body;
    public string $apiProvider;
    public string $apiKey;
    public Offer $offer;
    public function __construct(Offer $offer)
    {
        $this->className = config('theapp.wrangler.data_provider.price_location.class_name');
        $this->apiProvider = config('theapp.wrangler.data_provider.api_provider');
        $this->apiKey = config('theapp.wrangler.data_provider.api_key');
        $this->offer = $offer;
    }

    public function getOfferBody()
    {
        $response = Http::get($this->apiProvider, [
            'api_key' => $this->apiKey,
            'url' => $this->offer->url,
        ]);

        if(!$response->status() == 500) {
            $this->throwCantFetchDataException($this->offer->id);
        }

        if(!$response->successful()) {
            return false;
        }

        $this->body = $response->body();
        return true;

    }

    public function getOfferPrice(): float
    {
        $crawler = new Crawler($this->clearBody($this->body));
        $prices =  $crawler->filter($this->className)->each(function (Crawler $node, $i) {
            $price = $node->text();
            $price = str_replace('z', '', $price);
            $price = str_replace('ł', '', $price);
            return (float) str_replace(',', '.', $price);
        });

        if(empty($prices)) {
            $this->throwCantProccesOfferPriceException($this->offer->id, $this->body);
        }

        return $prices[0];
    }

    public function clearBody(string $body): string
    {
        $entities = str_replace('&nbsp;', ' ', $body);

        return html_entity_decode($entities);
    }
}
