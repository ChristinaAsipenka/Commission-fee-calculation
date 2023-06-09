<?php

declare(strict_types=1);

namespace App\Service\Normalizers;

use App\Entity\MoneyEntity;
use App\Interface\MoneyInterface;

class OutputNormalizer
{
    public function __construct(
        private readonly MoneyInterface $money
    ) {
    }

    public function convertMoneyEntityToString(MoneyEntity $moneyEntity): string
    {
        return $this->money->customBcround($moneyEntity)->getAmount();
    }
}
