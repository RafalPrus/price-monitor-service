<?php

namespace App\Services\Wrangler;

use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;
class WranglerService
{
    public string $className;
    public string $body;
    public string $apiProvider;
    public string $apiKey;
    public function __construct()
    {
        $this->className = config('theapp.wrangler.price.class_name');
        $this->apiProvider = config('theapp.wrangler.api_provider');
        $this->apiKey = config('theapp.wrangler.api_key');
    }

    public function canHandle(string $url, ?string $body = null)
    {
        $apiProvider = config();
        $apiKey = config();
        $response = Http::get($this->apiProvider, [
            'api_key' => $this->apiKey,
            'url' => $url,
        ]);

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
        return $prices[0];
    }

    public function clearBody(string $body): string
    {
        $entities = str_replace('&nbsp;', ' ', $body);

        return html_entity_decode($entities);
    }
}
