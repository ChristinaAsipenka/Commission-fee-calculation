<?php

declare(strict_types=1);

namespace App\Entity;

class MoneyEntity
{
    public string $amount;
    public string $currency;

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
