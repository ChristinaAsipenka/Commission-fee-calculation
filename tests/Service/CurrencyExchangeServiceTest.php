<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CurrencyExchangeService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class CurrencyExchangeServiceTest extends TestCase
{
    public function testRate()
    {
        $apiUrl = 'http://example.com/api';

        $mockResponses = [
            new MockResponse(json_encode(['rates' => ['USD' => 1.23]])),
        ];
        $client = new MockHttpClient($mockResponses);

        $currencyExchangeService = new CurrencyExchangeService($apiUrl, $client);

        $rate = $currencyExchangeService->rate('USD');
        $this->assertSame('1.23', $rate);
    }

    public function testRateWithException()
    {
        $apiUrl = 'http://example.com/api';

        $client = new MockHttpClient(function () {
            throw new \Exception('API is not available now');
        });

        $currencyExchangeService = new CurrencyExchangeService($apiUrl, $client);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Web-resource is not available now due to error: API is not available now');

        $currencyExchangeService->rate('USD');
    }
}
