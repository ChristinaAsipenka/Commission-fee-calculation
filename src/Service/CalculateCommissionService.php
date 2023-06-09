<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;
use App\Interface\CalculatorInterface;
use Symfony\Component\String\Exception\RuntimeException;

class CalculateCommissionService
{
    private array $calculatorMap;

    public function __construct(private readonly iterable $calculators)
    {
        foreach ($this->calculators as $calculator) {
            $this->calculatorMap[$calculator->getType()] = $calculator;
        }
    }

    public function calculate(TransactionEntity $data): MoneyEntity
    {
        return $this->getCalculator($data->getOperationType())->calculate($data);
    }

    private function getCalculator(string $operationType): CalculatorInterface
    {
        try {
            return $this->calculatorMap[$operationType];
        } catch (\Throwable $e) {
            throw new RuntimeException(sprintf('There is not any calculator for provided operation: %s', $this->calculatorMap[$operationType]));
        }
    }
}
