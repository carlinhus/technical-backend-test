<?php
declare(strict_types=1);

namespace App\Services;

interface ThirdPartyUrlShortenerInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function __invoke(string $url) : string;
}