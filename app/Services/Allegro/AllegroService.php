<?php

namespace App\Services\Allegro;

use App\Exceptions\InvalidBodyResponseException;
use App\Facades\FetchApi;
use App\Models\Offer;
use App\Services\AbstractOfferService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
class AllegroService extends AbstractOfferService
{
    public array $classNames;
    public string $body;
    public string $apiProvider;
    public string $apiKey;
    public Offer $offer;
    public function __construct(Offer $offer)
    {
        $this->classNames = config('theapp.allegro.data_provider.price_location.class_names');
        $this->offer = $offer;
    }

    public function getOfferBody(): bool
    {
        $this->body = FetchApi::makeRequest($this->offer)->body();
        return true;
    }

    public function getOfferPrice(): float
    {
        $crawler = new Crawler($this->clearBody($this->body));

        foreach ($this->classNames as $className) {
            $price = $crawler->filter($className)->each(function (Crawler $node, $i) {
                $price = $node->text();
                $price = explode(' ', $price);
                return (float) str_replace(',', '.', $price[0]);
            });

            if (!empty($price)) {
                break;
            }
        }
        

        if(empty($price)) {
            $this->throwCantProccesOfferPriceException($this->offer->id, $this->body);
        }

        return $price[0];
    }

    public function clearBody(string $body): string
    {
        $entities = str_replace('&nbsp;', ' ', $body);

        return html_entity_decode($entities);
    }
}
