<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Interfaces;

interface ConvertibleCurrency
{
    /**
     * Undocumented function.
     *
     * @param string    $currency
     * @param int|float $value
     *
     * @return string|float
     */
    public function convert($currency, $value);
}
