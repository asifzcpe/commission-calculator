<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\CommissionCalculator\Application;
use App\CommissionCalculator\Services\Commission;
use App\CommissionCalculator\Services\CurrencyExchange;
use App\CommissionCalculator\Services\Operations\Deposit;
use App\CommissionCalculator\Services\Operations\Withdraw;
use App\CommissionCalculator\Parsers\Csv;

class ApplicationTest extends TestCase
{
    protected $fileName;

    /**
     * Generate a csv file before starting the test runs
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fileName = 'sample.csv';

        // open csv file for writing
        $f = fopen($this->fileName, 'w');

        if ($f === false) {
            die('Error opening the file ' . $this->fileName);
        }

        // write each row at a time to a file
        foreach ($this->generateSampleData() as $row) {
            fputcsv($f, $row);
        }

        // close the file
        fclose($f);
    }

    /**
     * Remove the file after test is completed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if (!unlink($this->fileName)) {
            throw new \Exception('Something went wrong. File can not being deleted.');
        }
    }


    public function testHandleReturnsCorrectData(): void
    {
        // Create a new application instance
        $application = Application::make();

        // Set up the commission object
        $commission = new Commission();
        $commission->setOperations([
            'deposit' => Deposit::class,
            'withdraw' => Withdraw::class,
        ]);
        $commission->setCurrencyExchange(new CurrencyExchange());

        // Set the commission and data for the application
        $application->setCommission($commission);
        $application->setData(new Csv('input.csv'));

        // Call the handle method to process the data
        $collections = $application->handle();

        $calculatedCommissions = [];

        foreach ($collections as $collection) {
            $calculatedCommissions[] = $collection->getValue('roundUpFee');
        }

        $this->assertEquals(
            $this->getExpectedTestResult(),
            $calculatedCommissions
        );
    }


    private function generateSampleData(): array
    {
        return [
            ['2014-12-31', 4, 'private', 'withdraw', 1200.00, 'EUR'],
            ['2015-01-01', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 4, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', 1, 'private', 'deposit', 200.00, 'EUR'],
            ['2016-01-06', 2, 'business', 'withdraw', 300.00, 'EUR'],
            ['2016-01-06', 1, 'private', 'withdraw', 30000, 'JPY'],
            ['2016-01-07', 1, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-07', 1, 'private', 'withdraw', 100.00, 'USD'],
            ['2016-01-10', 1, 'private', 'withdraw', 100.00, 'EUR'],
            ['2016-01-10', 2, 'business', 'deposit', 10000.00, 'EUR'],
            ['2016-01-10', 3, 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-02-15', 1, 'private', 'withdraw', 300.00, 'EUR'],
            ['2016-02-19', 5, 'private', 'withdraw', 3000000, 'JPY']
        ];
    }

    private function getExpectedTestResult(): array
    {
        return [
            0.60,
            3.00,
            0.00,
            0.06,
            0.90,
            0,
            0.69,
            0.30,
            0.30,
            3.00,
            0.00,
            0.00,
            8608
        ];
    }
}
