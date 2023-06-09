<?php

declare(strict_types=1);

namespace App\Service\Calculator;

use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;
use App\Entity\UserEntity;
use App\Interface\CalculatorInterface;
use App\Service\MoneyService;
use App\Repository\TransactionStoreRepository;
use App\Service\Store\TransactionStore;

class WithdrawCalculator implements CalculatorInterface
{
    public function __construct(
        private readonly string                     $privateClientCommission,
        private readonly string                     $businessClientCommission,
        private readonly string                     $privateClientFreeAmount,
        private readonly string                     $privateClientFreeWithdraws,
        private readonly MoneyService               $moneyService,
        private readonly TransactionStore           $transactions,
        private readonly TransactionStoreRepository $transactionRepository,
    ) {
    }

    public function getType(): string
    {
        return CalculatorInterface::WITHDRAW_OPERATION;
    }

    public function calculate(TransactionEntity $transactionEntity): MoneyEntity
    {
        $money = clone $transactionEntity->getMoneyEntity();

        if (UserEntity::USER_TYPE_BUSINESS === $transactionEntity->getUserEntity()->getClientType()) {
            return $this->moneyService->getCommission($money, $this->businessClientCommission);
        }

        $this->transactions->setData($transactionEntity);
        $countOperationsPerWeek = $this->transactionRepository->getOperationsCountPerWeek($transactionEntity);
        $amountPerWeek = $this->transactionRepository->getAmountCountPerWeek($transactionEntity);

        if ($amountPerWeek->getAmount() <= $this->privateClientFreeAmount && $countOperationsPerWeek <= $this->privateClientFreeWithdraws) {
            return $this->moneyService->getCommission($money, '0');
        }

        if ($countOperationsPerWeek > $this->privateClientFreeWithdraws) {
            return $this->moneyService->getCommission($money, $this->privateClientCommission);
        }

        $notFreeAmount = $this->moneyService->getNotFreeAmount($amountPerWeek->getAmount(), $this->privateClientFreeAmount);

        if ($notFreeAmount >= $money->getAmount()) {
            return $this->moneyService->getCommission($money, $this->privateClientCommission);
        } else {
            $money->setAmount($notFreeAmount);

            return $this->moneyService->getCommission(

                $this->moneyService->convertToCurrency($money, $money->getCurrency()),
                $this->privateClientCommission
            );
        }
    }
}
