<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\String\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyExchangeService
{
    private array $rates = [];

    public function __construct(
        private readonly string              $apiUrl,
        private readonly HttpClientInterface $client,
    ) {
    }

    public function rate(string $currency): string
    {
        if (!$this->rates) {
            $this->rates = $this->loadRates();
        }

        return (string)$this->rates[$currency];
    }

    private function loadRates()
    {
        try {
            $response = $this->client->request('GET', $this->apiUrl);

            if ($response->getStatusCode() !== 200) {
                throw new RuntimeException('Web-resource is not available now.');
            }

            $content = $response->getContent();
            if (empty($content)) {
                throw new RuntimeException('Empty response body.');
            }

            $data = json_decode($content, true);
            if ($data === null || !isset($data['rates'])) {
                throw new RuntimeException('Invalid response format.');
            }

            return $data['rates'];
        } catch (\Throwable $e) {
            throw new RuntimeException(sprintf('Web-resource is not available now due to error: %s', $e->getMessage()));
        }
    }
}
