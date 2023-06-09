<?php

declare(strict_types=1);

namespace App\Tests\Service\Calculator;

use App\Entity\MoneyEntity;
use App\Factory\TransactionFactory;
use App\Repository\TransactionStoreRepository;
use App\Service\Calculator\WithdrawCalculator;
use App\Service\MoneyService;
use App\Service\Store\TransactionStore;
use PHPUnit\Framework\TestCase;

class WithdrawCalculatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCalculate(array $data)
    {
        $transactionFactory = new TransactionFactory();
        $transactionEntity = $transactionFactory->createTransaction($data);
        $moneyEntity = $transactionEntity->getMoneyEntity();
        $moneyService = $this->createMock(MoneyService::class);
        $transactions = $this->createMock(TransactionStore::class);
        $transactionRepository = $this->createMock(TransactionStoreRepository::class);
        $expectedResult = new MoneyEntity();

        $expectedResult->setAmount($data[6]);
        $expectedResult->setCurrency($data[5]);

        $privateClientCommissionMock = $this->createMock(MoneyService::class);
        $businessClientCommissionMock = $this->createMock(MoneyService::class);

        $privateClientCommission = '0';
        $businessClientCommission = '0.05';
        $privateClientFreeAmount = '1000';
        $privateClientFreeWithdraws = '3';

        if ($transactionEntity->getUserEntity()->getClientType() === 'private') {
            $privateClientCommissionMock->expects($this->once())
                ->method('getCommission')
                ->with($moneyEntity, $privateClientCommission)
                ->willReturn($expectedResult);

            $moneyService = $privateClientCommissionMock;
        } elseif ($transactionEntity->getUserEntity()->getClientType() === 'business') {
            $businessClientCommissionMock->expects($this->once())
                ->method('getCommission')
                ->with($moneyEntity, $businessClientCommission)
                ->willReturn($expectedResult);

            $moneyService = $businessClientCommissionMock;
        }

        $calculator = new WithdrawCalculator(
            $privateClientCommission,
            $businessClientCommission,
            $privateClientFreeAmount,
            $privateClientFreeWithdraws,
            $moneyService,
            $transactions,
            $transactionRepository
        );

        $result = $calculator->calculate($transactionEntity);

        $this->assertInstanceOf(MoneyEntity::class, $result);
        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider(): Iterable
    {
        yield [['2016-01-05', '1', 'private', 'withdraw', '200.00', 'EUR',  '0.06']];
        yield [['2016-01-06', '2', 'business', 'withdraw', '10000.00', 'EUR', '3.00']];
    }
}
