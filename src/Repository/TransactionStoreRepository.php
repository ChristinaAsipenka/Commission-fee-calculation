<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TransactionEntity;
use App\Entity\MoneyEntity;
use App\Service\MoneyService;
use App\Service\Store\TransactionStore;

class TransactionStoreRepository
{
    public function __construct(
        private readonly TransactionStore $transactionStore,
        private readonly MoneyService $moneyService,
        private readonly string $defaultCurrency
    ) {
    }

    public function getOperationsCountPerWeek(TransactionEntity $transactionEntity): int
    {
        return count($this->filteredRecords($transactionEntity));
    }

    public function getAmountCountPerWeek(TransactionEntity $transactionEntity): MoneyEntity
    {
        $totalAmount = new MoneyEntity();
        $filteredRecords = $this->filteredRecords($transactionEntity);

        $result = array_reduce($filteredRecords, function ($carry, $record) {
            $convertedMoney = $this->moneyService->convertToCurrency($record->getMoneyEntity(), $this->defaultCurrency);
            $amount = $convertedMoney->getAmount();
            return $carry + (float)$amount;
        }, 0);

        $totalAmount->setAmount((string)$result);
        $totalAmount->setCurrency($this->defaultCurrency);

        return $totalAmount;
    }

    private function filteredRecords(TransactionEntity $transactionEntity): array
    {
        $workData = $transactionEntity->getDate();
        $userId = $transactionEntity->getUserEntity()->getUserId();

        $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($workData)));
        $weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($workData)));

        return array_filter($this->transactionStore->getData(), function ($record) use ($weekStart, $weekEnd, $userId) {
            $recordDate = $record->getDate();
            $recordUserId = $record->getUserEntity()->getUserId();

            return $recordDate >= $weekStart && $recordDate <= $weekEnd && $recordUserId == $userId;
        });
    }
}
