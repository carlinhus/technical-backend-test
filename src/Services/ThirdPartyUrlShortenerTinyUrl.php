<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ThirdPartyUrlShortenerTinyUrl implements ThirdPartyUrlShortenerInterface
{
    private const BASE_URL = "https://tinyurl.com/api-create.php?url=";

    /**
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    public function __invoke(string $url): string
    {
        $httpClient = new Client();
        $response = $httpClient->request("GET", self::BASE_URL . $url);
        return $response->getBody()->getContents();
    }
}