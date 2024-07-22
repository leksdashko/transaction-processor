<?php

namespace App\Services;

use App\Contracts\CurrencyRateProviderInterface;

class CurrencyRateProvider implements CurrencyRateProviderInterface
{
    private $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getExchangeRates(): array
    {
        $response = @file_get_contents($this->apiUrl);
        if ($response === FALSE) {
            throw new \Exception("Unable to connect to exchange rates service.");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON response from API");
        }

        return $data;
    }
}
