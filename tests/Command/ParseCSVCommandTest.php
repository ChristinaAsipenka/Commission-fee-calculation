<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\ParseCSVCommand;
use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;
use App\Factory\TransactionFactory;
use App\Service\CalculateCommissionService;
use App\Service\Normalizers\OutputNormalizer;
use App\Service\ParserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ParseCSVCommandTest extends TestCase
{
    private ParseCSVCommand $command;
    private MockObject $parserServiceMock;
    private MockObject $calculateCommissionServiceMock;
    private MockObject $outputNormalizerMock;
    private MockObject $transactionFactoryMock;

    protected function setUp(): void
    {
        $this->parserServiceMock = $this->createMock(ParserService::class);
        $this->calculateCommissionServiceMock = $this->createMock(CalculateCommissionService::class);
        $this->outputNormalizerMock = $this->createMock(OutputNormalizer::class);
        $this->transactionFactoryMock = $this->createMock(TransactionFactory::class);

        $this->command = new ParseCSVCommand(
            $this->parserServiceMock,
            $this->calculateCommissionServiceMock,
            $this->outputNormalizerMock,
            $this->transactionFactoryMock
        );
    }

    public function testExecute(): void
    {
        $filename = realpath(__DIR__ . '/../../txt/operations.csv');
        $rows = [
            ['2014-12-31', '4', 'private', 'withdraw', '1200.00', 'EUR'],
            ['2015-01-01', '4', 'private', 'withdraw', '1000.00', 'EUR'],
            ['2016-01-05', '4', 'private', 'withdraw', '1000.00', 'EUR']
        ];

        $feeMoney = new MoneyEntity();
        $feeMoney->setAmount('3.00');
        $feeMoney->setCurrency('EUR');

        $this->parserServiceMock->expects($this->once())
            ->method('parse')
            ->with($filename)
            ->willReturn($rows);

        $this->transactionFactoryMock->expects($this->exactly(3))
            ->method('createTransaction')
            ->willReturnOnConsecutiveCalls(
                $this->createTransaction($rows[0]),
                $this->createTransaction($rows[1]),
                $this->createTransaction($rows[2]),
            );
        $this->calculateCommissionServiceMock->expects($this->exactly(3))
            ->method('calculate')
            ->willReturn($feeMoney);

        $this->outputNormalizerMock->expects($this->exactly(3))
            ->method('convertMoneyEntityToString')
            ->with($feeMoney)
            ->willReturn('3.00');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['filename' => $filename]);

        $this->assertSame(0, $commandTester->getStatusCode());
    }

    private function createTransaction(array $data): TransactionEntity
    {
        $transactionFactory = new TransactionFactory();
        return $transactionFactory->createTransaction($data);
    }
}
