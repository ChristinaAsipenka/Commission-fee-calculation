<?php

declare(strict_types=1);

namespace App\Entity;

class UserEntity
{
    const USER_TYPE_PRIVATE = 'private';
    const USER_TYPE_BUSINESS = 'business';

    public string $clientType;

    public string $userId;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getClientType(): string
    {
        return $this->clientType;
    }

    public function setClientType(string $clientType): void
    {
        $this->clientType = $clientType;
    }
}
