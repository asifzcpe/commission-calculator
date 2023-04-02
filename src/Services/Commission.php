<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Services;

use App\CommissionCalculator\Interfaces\Commissionable;
use App\CommissionCalculator\Traits\ExchangeSetterTrait;
use App\CommissionCalculator\Transformers\Collection;

class Commission implements Commissionable
{
    use ExchangeSetterTrait;

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $operations = [
        'deposit' => Operations\Deposit::class,
        'withdraw' => Operations\Withdraw::class,
    ];

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setOperations(array $operations = [])
    {
        $this->operations = $operations;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection)
    {
        $class = $this->operations[$collection->operationType()];
        $operator = new $class($collection);
        $operator->setCurrencyExchange($this->exchange);
        $fee = $operator->fee();

        $collection->setValue('rawFee', $fee);
        $collection->setValue('roundUpFee', Math::roundUp($fee, $this->countDecimalPlaces($collection->amount())));
        $collection->setValue('convertedFee', Math::roundUp($this->exchange->convert(
            $collection->currency(),
            $fee
        )));

        return $fee;
    }

    private function countDecimalPlaces($amount)
    {
        // Convert the number to a string
        $number_str = (string) $amount;

        // Check if the number has a decimal point
        if (strpos($number_str, '.') !== false) {
            // Count the number of digits after the decimal point
            $decimal_places = strlen(substr($number_str, strpos($number_str, '.') + 1));

            return $decimal_places;
        } else {
            // The number has no decimal places
            return 0;
        }
    }
}
