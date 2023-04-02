<?php

declare(strict_types=1);

namespace App\CommissionCalculator;

use App\CommissionCalculator\Interfaces\Collectionable;
use App\CommissionCalculator\Interfaces\Commissionable;
use App\CommissionCalculator\Services\Commission;
use App\CommissionCalculator\Services\CurrencyExchange;
use App\CommissionCalculator\Traits\MakeableTrait;

class Application
{
    use MakeableTrait;

    /**
     * Undocumented variable.
     *
     * @var \App\CommissionCalculator\Interfaces\ShouldComputeCommissions
     */
    protected $commission;

    /**
     * Undocumented variable.
     *
     * @var \App\CommissionCalculator\Interfaces\ShouldProvideCollection
     */
    protected $collector;

    /**
     * Undocumented function.
     */
    public function __construct()
    {
        $exchange = new CurrencyExchange();
        $commission = new Commission();
        $commission->setCurrencyExchange($exchange);

        $this->setCommission($commission);
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setCommission(Commissionable $commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return self
     */
    public function setData(Collectionable $collector)
    {
        $this->collector = $collector;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    public function handle()
    {
        foreach ($this->collector->collections() as $collection) {
            $this->commission->compute($collection);
        }

        return $this->collector->collections();
    }
}
