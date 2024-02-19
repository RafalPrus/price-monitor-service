<?php

namespace Tests\Unit;

use App\Enums\AvailableStore;
use App\Services\UrlService;
use PHPUnit\Framework\TestCase;

class UrlServiceTest extends TestCase
{
    /**
     * @dataProvider urlProvider
     * @test
     */
    public function url_service_is_returns_correct_domain($url, $result): void
    {
        $urlService = new UrlService();
        $domain = $urlService->getDomain($url);
        $this->assertEquals($result, $domain);
        $this->assertTrue(in_array($domain, AvailableStore::getArrayOfValues()));
    }

    public static function urlProvider(): array
    {
        return [
            [
                "https://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
                'allegro.pl'
            ],
            [
                "https://www.allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
                'allegro.pl'
            ],
            [
                "http://www.allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
                'allegro.pl'
            ],
            [
                "http://allegro.pl/oferta/zakreslacz-fluorescencyjny-office-products-6-sztuk-12233966019?reco_id=55cccecc-bdc0-11ee-8b5c-6ad8aac69f9a&sid=214dee23af5dacba6db9ec985d2421ccbc3b0218e9654c1f9eeca650c7d606e7",
                'allegro.pl'
            ],
            [
                'https://eu.wrangler.com/pl-pl/shop/mezczyzni/dzinsy-i-odziez/chinosy-i-spodnie-nie-z-denimu/pant-critter-in-plaza-taupe-112350887.html?merchCategory=c010000',
                'eu.wrangler.com'
            ]
        ];
    }
}
