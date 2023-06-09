<?php

declare(strict_types=1);

namespace App\Tests\Service\Store;

use App\Factory\TransactionFactory;
use PHPUnit\Framework\TestCase;
use App\Service\Store\TransactionStore;

class TransactionStoreTest extends TestCase
{
    public function testGetData(): void
    {
        $store = new TransactionStore();

        $this->assertSame([], $store->getData());
    }
    /**
     * @dataProvider dataProvider
     */
    public function testSetData($data): void
    {
        $transactionStore = new TransactionStore();
        $transactionFactory = new TransactionFactory();
        $transactionEntity = $transactionFactory->createTransaction($data);

        $transactionStore->setData($transactionEntity);
        $data = $transactionStore->getData();

        $this->assertCount(1, $data);
        $this->assertSame($transactionEntity, $data[0]);
    }
    public static function dataProvider()
    {
        yield [['2016-01-05', '1', 'private', 'deposit', '200.00', 'EUR',  '0.06']];
    }
}
