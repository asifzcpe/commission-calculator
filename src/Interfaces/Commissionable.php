<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Interfaces;

use App\CommissionCalculator\Transformers\Collection;

interface Commissionable
{
    /**
     * Undocumented function.
     *
     * @return string|float
     */
    public function compute(Collection $collection);
}
