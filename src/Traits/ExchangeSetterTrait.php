<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Traits;

use App\CommissionCalculator\Interfaces\ConvertibleCurrency;

trait ExchangeSetterTrait
{
    /**
     * Undocumented variable.
     *
     * @var \App\CommissionCalculator\Interfaces\ConvertibleCurrency
     */
    protected $exchange;

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCurrencyExchange(ConvertibleCurrency $exchange)
    {
        $this->exchange = $exchange;

        return $this;
    }
}
