<?php

namespace App\Services\Wrangler;

use App\Facades\FetchApi;
use App\Models\Offer;
use App\Services\AbstractOfferService;
use Symfony\Component\DomCrawler\Crawler;

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
        $this->offer = $offer;
    }

    public function getOfferBody()
    {
        $this->body = FetchApi::makeRequest($this->offer)->body();
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
