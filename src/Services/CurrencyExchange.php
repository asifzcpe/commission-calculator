<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Services;

use App\CommissionCalculator\Interfaces\ConvertibleCurrency;

class CurrencyExchange implements ConvertibleCurrency
{
    public const BASE_CURRENCY = 'EUR';

    /**
     * Override this method if you want to use API to
     * fetch latest exchange rate!
     */
    public function getLatestExchange(string $currency)
    {
        $exchangeRate = ConversionRate::get($currency);

        if (!isset($exchangeRate)) {
            throw new \Exception("[$currency] is not supported yet.");
        }

        return self::BASE_CURRENCY === $currency ? 1 : $exchangeRate;
    }

    /**
     * Undocumented function.
     *
     * @param string $currency
     * @param mixed  $value
     *
     * @return string
     */
    public function convert($currency, $value)
    {
        $latestExchange = $this->getLatestExchange($currency);

        return Math::div(
            (string) $value,
            $latestExchange
        );
    }

    public function convertBack($currency, $value)
    {
        $latestExchange = $this->getLatestExchange($currency);

        return Math::mul(
            (string) $value,
            $latestExchange
        );
    }
}
