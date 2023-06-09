<?php

declare(strict_types=1);

namespace App\Interface;

use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;

interface CalculatorInterface
{
    const DEPOSIT_OPERATION = 'deposit';
    const WITHDRAW_OPERATION = 'withdraw';

    public function getType(): string;

    public function calculate(TransactionEntity $transactionEntity): MoneyEntity;
}
