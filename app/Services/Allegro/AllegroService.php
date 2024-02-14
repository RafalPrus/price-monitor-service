<?php

namespace App\Services\Allegro;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Panther\PantherTestCase;
class AllegroService extends PantherTestCase
{
    public string $className;
    public string $body;
    public function __construct()
    {
        $this->className = config('theapp.allegro.price.class_name');
    }

    public function canHandle(string $url, ?string $body = null): bool
    {
        $browser = new HttpBrowser(HttpClient::create());
        if(empty($body)) {
            $browser->request('GET', $url);
            $response = $browser->getResponse();

            if($response->getStatusCode() != 200) {
                return false;
            }

            $this->body = $response->getContent();
            return true;
        }

        // dodane na potrzeby testowania
        $this->body = $body;
        return true;

    }

    public function getOfferPrice(): float
    {
        $crawler = new Crawler($this->clearBody($this->body));

        return $crawler->filter($this->className)->each(function (Crawler $node, $i) {
            $price = $node->text();
            $price = explode(' ', $price);
            return (float) str_replace(',', '.', $price[0]);
        })[0];
    }

    public function clearBody(string $body): string
    {
        $entities = str_replace('&nbsp;', ' ', $body);

        return html_entity_decode($entities);
    }
}
