<?php

declare(strict_types=1);

namespace App\Tests\Service\Calculator;

use App\Entity\MoneyEntity;
use App\Factory\TransactionFactory;
use App\Service\Calculator\DepositCalculator;
use App\Service\MoneyService;
use PHPUnit\Framework\TestCase;

class DepositCalculatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCalculate(array $data)
    {
        $transactionFactory = new TransactionFactory();
        $moneyService = $this->createMock(MoneyService::class);
        $depositPercent = '0.05';
        $expectedResult = new MoneyEntity();
        $expectedResult->setAmount($data[6]);
        $expectedResult->setCurrency($data[5]);
        $transactionEntity =$transactionFactory->createTransaction($data);
        $moneyEntity = $transactionEntity->getMoneyEntity();

        $moneyService->expects($this->once())
        ->method('getCommission')
        ->with($moneyEntity, $depositPercent)
        ->willReturn($expectedResult);
        $calculator = new DepositCalculator($moneyService, $depositPercent);

        $result = $calculator->calculate($transactionEntity);

        $this->assertInstanceOf(MoneyEntity::class, $result);

        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedResult, $result);
    }

    public static function dataProvider()
    {
        yield [['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR',  '0.06']];
        yield [['2016-01-06', '2', 'business', 'withdraw', '10000.00', 'EUR', '3.00']];
    }
}
