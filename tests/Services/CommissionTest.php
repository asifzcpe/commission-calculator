<?php

declare(strict_types=1);

use App\CommissionCalculator\Services\Commission;
use App\CommissionCalculator\Services\CurrencyExchange;
use App\CommissionCalculator\Transformers\Collection;
use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    private $commission;

    protected function setUp(): void
    {
        $this->commission = new Commission();
    }

    public function testComputeReturnsCorrectFee()
    {
        $collection = new Collection([
            '2019-12-09',
            $userId = 1,
            'private',
            'deposit',
            1000,
            'EUR'
        ]);

        $expectedFee = 0.30;

        $this->commission->setCurrencyExchange(new CurrencyExchange());
        $actualFee = $this->commission->compute($collection);

        $this->assertEquals($expectedFee, $actualFee);
    }
}
