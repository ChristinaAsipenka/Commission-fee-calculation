<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\MoneyEntity;
use App\Factory\TransactionFactory;
use App\Service\MoneyService;
use App\Service\Store\TransactionStore;
use App\Repository\TransactionStoreRepository;
use PHPUnit\Framework\TestCase;

class TransactionStoreRepositoryTest extends TestCase
{
    public function testGetOperationsCountPerWeek(): void
    {
        $transactionStore = new TransactionStore();
        $moneyService = $this->createMock(MoneyService::class);
        $defaultCurrency = 'EUR';

        $transactionFactory = new TransactionFactory();
        $transactionEntity =$transactionFactory->
        createTransaction(['2016-01-06', '2', 'business', 'withdraw', '10000.00', 'EUR', '3.00']);

        $transactionStoreRepository = new TransactionStoreRepository($transactionStore, $moneyService, $defaultCurrency);
        $result = $transactionStoreRepository->getOperationsCountPerWeek($transactionEntity);

        $this->assertSame(0, $result);
    }

    public function testGetAmountCountPerWeek(): void
    {
        $transactionStore = new TransactionStore();
        $moneyService = $this->createMock(MoneyService::class);
        $defaultCurrency = 'EUR';

        $transactionFactory = new TransactionFactory();
        $transactionEntity =$transactionFactory->
        createTransaction(['2016-01-06', '2', 'business', 'withdraw', '10000.00', 'EUR', '3.00']);

        $transactionStoreRepository = new TransactionStoreRepository($transactionStore, $moneyService,$defaultCurrency);
        $result = $transactionStoreRepository->getAmountCountPerWeek($transactionEntity);

        $this->assertInstanceOf(MoneyEntity::class, $result);
        $this->assertSame('0', $result->getAmount());
        $this->assertSame($defaultCurrency, $result->getCurrency());
    }
}
