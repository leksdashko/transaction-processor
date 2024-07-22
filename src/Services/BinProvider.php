<?php

namespace App\Services;

use App\Contracts\BinProviderInterface;

class BinProvider implements BinProviderInterface
{
    private $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getBinData(string $bin): array
    {
        $response = @file_get_contents($this->apiUrl . $bin);
        if ($response === FALSE) {
            throw new \Exception("Unable to connect to BIN list service.");
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON response API");
        }

        return $data;
    }
}
