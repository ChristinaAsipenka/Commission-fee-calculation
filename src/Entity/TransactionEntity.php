<?php

declare(strict_types=1);

namespace App\Entity;

class TransactionEntity
{
    private UserEntity $userEntity;
    private string $date;
    private string $operationType;
    private MoneyEntity $moneyEntity;

    public function __construct(
        string      $date,
        string      $operationType,
        UserEntity  $userEntity,
        MoneyEntity $moneyEntity
    ) {
        $this->date = $date;
        $this->operationType = $operationType;
        $this->userEntity = $userEntity;
        $this->moneyEntity = $moneyEntity;
    }

    public function getUserEntity(): UserEntity
    {
        return $this->userEntity;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function getMoneyEntity(): MoneyEntity
    {
        return $this->moneyEntity;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
