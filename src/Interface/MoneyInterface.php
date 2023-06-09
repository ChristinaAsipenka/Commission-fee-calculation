<?php

declare(strict_types=1);

namespace App\Interface;

use App\Entity\MoneyEntity;

interface MoneyInterface
{
    const CALC_PRECISION = 4;
    public function convertToCurrency(MoneyEntity $moneyEntity, string $currency): MoneyEntity;
    public function getCommission(MoneyEntity $moneyEntity, string $commissionPercent): MoneyEntity;
    public function customBcround(MoneyEntity $moneyEntity): MoneyEntity;
}
