<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Services;

use PHPUnit\Framework\TestCase;

class CurrencyExchangeTest extends TestCase
{
    private $exchange;

    protected function setUp(): void
    {
        $this->exchange = new CurrencyExchange();
    }

    public function testGetLatestExchangeReturnsOneForBaseCurrency()
    {
        $latestExchange = $this->exchange->getLatestExchange(CurrencyExchange::BASE_CURRENCY);

        $this->assertSame(1, $latestExchange);
    }


    public function testConvertReturnsCorrectValue()
    {
        $this->assertEquals(Math::roundUp($this->exchange->convert('EUR', 1)), 1);
        $this->assertEquals(Math::roundUp($this->exchange->convert('JPY', 129.53)), 0.99);
        $this->assertEquals(Math::roundUp($this->exchange->convert('USD', 1.1497)), 1.02);
        $this->assertEquals(Math::roundUp($this->exchange->convert('JPY', 100)), 0.77);
        $this->assertEquals(Math::roundUp($this->exchange->convert('USD', 100)), 88.58);
    }
}
