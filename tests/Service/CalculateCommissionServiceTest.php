<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\MoneyEntity;
use App\Factory\TransactionFactory;
use App\Interface\CalculatorInterface;
use App\Service\CalculateCommissionService;
use PHPUnit\Framework\TestCase;

class CalculateCommissionServiceTest extends TestCase
{
    public function testCalculate(): void
    {
        // Mock the necessary dependencies
        $calculatorMock = $this->createMock(CalculatorInterface::class);
        $calculatorMock->expects($this->once())
            ->method('getType')
            ->willReturn('deposit');

        $transactionFactory = new TransactionFactory();
        $transaction =$transactionFactory->
        createTransaction(['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR', '0.06']);
        $expectedResult = new MoneyEntity();
        $expectedResult->setAmount('0.06');
        $expectedResult->setCurrency('EUR');
        $moneyEntityMock = $expectedResult;

        $calculatorMock->expects($this->once())
            ->method('calculate')
            ->willReturn($moneyEntityMock);

        $calculateCommissionService = new CalculateCommissionService([$calculatorMock]);
        $result = $calculateCommissionService->calculate($transaction);
        $this->assertEquals($expectedResult, $result);

        $calculatorMockWithdraw = $this->createMock(CalculatorInterface::class);
        $calculatorMockWithdraw->expects($this->once())
            ->method('getType')
            ->willReturn('withdraw');

        $transactionFactory = new TransactionFactory();
        $transaction =$transactionFactory->
        createTransaction(['2016-01-06', '2', 'business', 'withdraw', '10000.00', 'EUR', '3.00']);
        $expectedResult = new MoneyEntity();
        $expectedResult->setAmount('3.00');
        $expectedResult->setCurrency('EUR');
        $moneyEntityMock = $expectedResult;

        $calculatorMockWithdraw->expects($this->once())
            ->method('calculate')
            ->willReturn($moneyEntityMock);

        $calculateCommissionService = new CalculateCommissionService([$calculatorMockWithdraw]);
        $result = $calculateCommissionService->calculate($transaction);
        $this->assertEquals($expectedResult, $result);
    }
}
