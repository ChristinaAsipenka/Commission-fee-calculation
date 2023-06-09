<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;
use App\Interface\CalculatorInterface;
use App\Service\MoneyService;

class DepositCalculator implements CalculatorInterface
{
    public function __construct(
        private readonly MoneyService $moneyService,
        private readonly string       $depositPercent,
    ) {
    }

    public function getType(): string
    {
        return CalculatorInterface::DEPOSIT_OPERATION;
    }

    public function calculate(TransactionEntity $transactionEntity): MoneyEntity
    {
        return $this->moneyService->getCommission($transactionEntity->getMoneyEntity(), $this->depositPercent);
    }
}
