<?php

namespace App\Services\Allegro;

use App\Exceptions\InvalidBodyResponseException;
use App\Models\Offer;
use App\Services\AbstractOfferService;
use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
class AllegroService extends AbstractOfferService
{
    public string $className;
    public string $body;
    public string $apiProvider;
    public string $apiKey;
    public Offer $offer;
    public function __construct(Offer $offer)
    {
        $this->className = config('theapp.allegro.data_provider.price_location.class_name');
        $this->apiProvider = config('theapp.allegro.data_provider.api_provider');
        $this->apiKey = config('theapp.allegro.data_provider.api_key');
        $this->offer = $offer;
    }

    public function canHandle(): bool
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

    public function getOfferPrice(): float|bool
    {
        $crawler = new Crawler($this->clearBody($this->body));

        $price = $crawler->filter($this->className)->each(function (Crawler $node, $i) {
            $price = $node->text();
            $price = explode(' ', $price);
            return (float) str_replace(',', '.', $price[0]);
        });

        if(empty($price)) {
            $this->throwCantProccesOfferPriceException($this->offer->url, $this->body);
        }
        
        return $price[0];
    }

    public function clearBody(string $body): string
    {
        $entities = str_replace('&nbsp;', ' ', $body);

        return html_entity_decode($entities);
    }
}
