<?php

use App\CommissionCalculator\Services\CurrencyExchange;
use PHPUnit\Framework\TestCase;
use App\CommissionCalculator\Services\Operations\Withdraw;
use App\CommissionCalculator\Transformers\Collection;

class WithdrawTest extends TestCase
{

    private function createInstance($datum)
    {
        $instance = new Withdraw(new Collection($datum));

        $instance->setCurrencyExchange(new CurrencyExchange());

        return $instance;
    }

    public function testBusinessWithdraw()
    {
        $instance = $this->createInstance([
            '2019-01-01',
            4,
            'business',
            'withdraw',
            5000.00,
            'EUR',
        ]);

        // amount * (% commission fee / 100%)
        // 5000 * (0.3% / 100%)
        // 5000 * 0.003
        // should be 15
        $this->assertEquals($instance->fee(), 15.00);
    }

    public function testPrivate()
    {
        $userId = 1;
        $records = [
            [
                'collection' => ['2019-12-02', $userId, 'private', 'withdraw', 300, 'EUR'],
                'result'     => 0.00,
            ],
            [
                'collection' => ['2019-12-02', $userId, 'private', 'withdraw', 300, 'EUR'],
                'result'     => 0.00,
            ],
            [
                'collection' => ['2019-12-03', $userId, 'private', 'withdraw', 300, 'EUR'],
                'result'     => 0.00,
            ],
            [
                // 1000 allocated free of the week
                'collection' => ['2019-12-08', $userId, 'private', 'withdraw', 100, 'EUR'],
                'result'     => 0.00,
            ],
            [
                'collection' => ['2019-12-08', $userId, 'private', 'withdraw', 100, 'EUR'],
                'result'     => 0.30,
            ],
            [
                'collection' => ['2019-12-08', $userId, 'private', 'withdraw', 300, 'EUR'],
                'result'     => 0.90,
            ],
            [
                'collection' => ['2019-12-09', $userId, 'private', 'withdraw', 1100, 'EUR'],
                'result'     => 0.30,
            ],
        ];

        foreach ($records as $record) {
            $instance = $this->createInstance($record['collection']);

            $this->assertEquals($instance->fee(), $record['result']);
        }
    }
}
