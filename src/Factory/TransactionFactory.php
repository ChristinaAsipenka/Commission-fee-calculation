<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\MoneyEntity;
use App\Entity\TransactionEntity;
use App\Entity\UserEntity;

class TransactionFactory
{
    public function createTransaction(array $data): TransactionEntity
    {
        $user = new UserEntity();
        $user->setUserId($data[1]);
        $user->setClientType($data[2]);

        $money = new MoneyEntity();
        $money->setAmount($data[4]);
        $money->setCurrency($data[5]);

        $date = $data[0];
        $operationType = $data[3];

        return new TransactionEntity($date, $operationType, $user, $money);
    }
}
