<?php

declare(strict_types=1);

namespace App\Tests\Service\Normalizers;

use App\Entity\MoneyEntity;
use App\Interface\MoneyInterface;
use App\Service\Normalizers\OutputNormalizer;
use PHPUnit\Framework\TestCase;

class OutputNormalizerTest extends TestCase
{
    public function testConvertMoneyEntityToString(): void
    {
        $money = $this->createMock(MoneyInterface::class);
        $moneyEntity = new MoneyEntity();
        $moneyEntity->setAmount('10.5678');
        $moneyEntity->setCurrency('EUR');

        $money->expects($this->once())
            ->method('customBcround')
            ->with($this->isInstanceOf(MoneyEntity::class))
            ->willReturnCallback(function ($moneyEntity) {
                $res = new MoneyEntity();
                $res->setAmount('10.57');
                $res->setCurrency('EUR');
                return $res;
            });


        $outputNormalizer = new OutputNormalizer($money);
        $result = $outputNormalizer->convertMoneyEntityToString($moneyEntity);

        $this->assertSame('10.57', $result);
    }
}
