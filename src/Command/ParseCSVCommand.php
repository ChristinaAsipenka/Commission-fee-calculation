<?php

declare(strict_types=1);

namespace App\Command;

use App\Factory\TransactionFactory;
use App\Service\CalculateCommissionService;
use App\Service\Normalizers\OutputNormalizer;
use App\Service\ParserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Exception\RuntimeException;

#[AsCommand(
    name: 'app:calculate-commission',
    description: 'Calculate commission for withdraw and deposit.'
)]
class ParseCSVCommand extends Command
{
    public function __construct(
        private readonly ParserService              $parserManager,
        private readonly CalculateCommissionService $calculateCommission,
        private readonly OutputNormalizer           $outputNormalizer,
        private readonly TransactionFactory         $transactionFactory
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('filename');

        if (!file_exists($file)) {
            throw new RuntimeException('Cannot find provided file ');
        }

        foreach ($this->parserManager->parse($file) as $row) {
            $transaction = $this->transactionFactory->createTransaction($row);
            $fee = $this->outputNormalizer->convertMoneyEntityToString($this->calculateCommission->calculate($transaction));
            $output->writeln($fee);
        }

        return Command::SUCCESS;
    }
}
