<?php

namespace App\Services;

class UrlService
{
    public static function getDomain(string $url): string
    {
        $domain = str_replace('www.', '', $url);

        $url = self::handleHttpPrefix($url);

        $url = strtolower($url);

        return parse_url($url, PHP_URL_HOST);
    }

    public static function handleHttpPrefix(string $url): string
    {
        if(!str_contains($url, 'http')) {
            $url = "http://" . $url;
        }

        return $url;
    }
}
