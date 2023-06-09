<?php

declare(strict_types=1);

namespace App\Service;

class CurrencyService
{
    private int $defaultPrecision = 2;

    public function __construct(private readonly array $currencyPrecision)
    {
    }

    public function getCurrencyPrecision(string $currency): int
    {
        return array_key_exists($currency, $this->currencyPrecision) ?
            $this->currencyPrecision[$currency] : $this->defaultPrecision;
    }
}
