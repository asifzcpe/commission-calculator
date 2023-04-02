<?php

declare(strict_types=1);

namespace Tests\App\CommissionCalculator\Services\Operations;

use App\CommissionCalculator\Services\CurrencyExchange;
use App\CommissionCalculator\Services\Operations\Deposit;
use App\CommissionCalculator\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class DepositTest extends TestCase
{
    private $collection;

    protected function setUp(): void
    {
        $this->collection = new Collection([
            '2014-12-31', 4, 'private', 'deposit', 200.00, 'EUR'
        ]);
    }

    public function testFee(): void
    {
        $instance = new Deposit($this->collection);
        $instance->setCurrencyExchange(new CurrencyExchange());

        // amount * (% commission fee / 100%)
        // 200 * (0.03% / 100%)
        // 200 * 0.0003
        // should be 0.06
        $this->assertEquals($instance->fee(), 0.06);
    }
}
