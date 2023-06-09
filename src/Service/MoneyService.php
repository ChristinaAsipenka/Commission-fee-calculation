<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MoneyEntity;
use App\Interface\MoneyInterface;

class MoneyService implements MoneyInterface
{
    public function __construct(
        private readonly CurrencyExchangeService $currencyExchanger,
        private readonly string                  $defaultCurrency,
        private readonly CurrencyService         $currencyService
    ) {
    }

    public function convertToCurrency(MoneyEntity $moneyEntity, string $currency): MoneyEntity
    {
        if ($this->defaultCurrency === $moneyEntity->getCurrency()) {
            return $moneyEntity;
        }

        $convertedMoney = new MoneyEntity();

        if ($currency === $this->defaultCurrency) {
            $convertedMoney->setAmount(bcdiv(
                $moneyEntity->getAmount(),
                $this->currencyExchanger->rate($moneyEntity->getCurrency()),
                MoneyInterface::CALC_PRECISION
            ));
            $convertedMoney->setCurrency($this->defaultCurrency);
        } else {
            $convertedMoney->setAmount(bcmul(
                $moneyEntity->getAmount(),
                $this->currencyExchanger->rate($moneyEntity->getCurrency()),
                MoneyInterface::CALC_PRECISION
            ));
            $convertedMoney->setCurrency($moneyEntity->getCurrency());
        }

        return $convertedMoney;
    }

    public function getCommission(MoneyEntity $moneyEntity, string $commissionPercent): MoneyEntity
    {
        $resultMoney = new MoneyEntity();
        $resultMoney->setAmount(
            bcdiv(
                bcmul(
                    $commissionPercent,
                    $moneyEntity->getAmount(),
                    MoneyInterface::CALC_PRECISION
                ),
                '100',
                MoneyInterface::CALC_PRECISION
            )
        );
        $resultMoney->setCurrency($moneyEntity->getCurrency());

        return $resultMoney;
    }

    public function getNotFreeAmount($amountPerWeek, $privateClientFreeAmount): string
    {
        return bcsub($amountPerWeek, $privateClientFreeAmount, MoneyInterface::CALC_PRECISION);
    }

    public function customBcround(MoneyEntity $moneyEntity): MoneyEntity
    {
        $roundedMoney = new MoneyEntity();
        $roundedMoney->setCurrency($moneyEntity->getCurrency());
        $number = $moneyEntity->getAmount();
        $currencyPrecision = $this->currencyService->getCurrencyPrecision($moneyEntity->getCurrency());
        if (str_contains($number, '.')) {
            if ($number[0] != '-') {
                $rounded = bcadd($number, '0.' . str_repeat('0', $currencyPrecision) . '9', $currencyPrecision);
            } else {
                $rounded = bcsub($number, '0.' . str_repeat('0', $currencyPrecision) . '1', $currencyPrecision);
            }
            $roundedMoney->setAmount(bcadd($rounded, '0', $currencyPrecision));
            return $roundedMoney;
        }
        $roundedMoney->setAmount($number);

        return $roundedMoney;
    }
}
