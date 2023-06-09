<?php

declare(strict_types=1);

namespace App\Service\Store;

use App\Entity\TransactionEntity;

class TransactionStore
{
    private array $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    public function getData(): array
    {
        return $this->transactions;
    }

    public function setData(TransactionEntity $transactionEntity): void
    {
        $this->transactions[] = $transactionEntity;
    }
}
