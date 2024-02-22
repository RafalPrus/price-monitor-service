<?php

namespace App\Services;

use App\Facades\FetchApi;
use App\Models\Offer;
use App\Services\AbstractOfferService;
use Symfony\Component\DomCrawler\Crawler;

class FrontUrlService
{
    public string $basicUrl;
    public function __construct()
    {
        $this->basicUrl = config('front_url.base_url') . '/';
    }
    public function getConfirmEmailUrl()
    {
        return $this->basicUrl . config('front_url.adresses.email_confirm');
    }
}