<?php

declare(strict_types=1);

use App\CommissionCalculator\Application;
use App\CommissionCalculator\Parsers\Csv;
use App\CommissionCalculator\Services\Commission;
use App\CommissionCalculator\Services\CurrencyExchange;
use App\CommissionCalculator\Services\Operations\Deposit;
use App\CommissionCalculator\Services\Operations\Withdraw;

require_once __DIR__ . '/bootstrap.php';

// Check if the command line argument is set
if (!isset($argv[1])) {
    echo 'Usage: php index.php [path/to/csv]' . PHP_EOL;
    exit(1);
}

$csvPath = $argv[1];

// Check if the CSV file exists
if (!file_exists($csvPath)) {
    echo 'Error: CSV file not found.' . PHP_EOL;
    exit(1);
}

$commission = new Commission();
$commission->setOperations([
    'deposit' => Deposit::class,
    'withdraw' => Withdraw::class,
]);
$commission->setCurrencyExchange(new CurrencyExchange());

try {
    $collections = Application::make()
        ->setCommission($commission)
        ->setData(new Csv($csvPath))
        ->handle();
    require_once __DIR__ . '/result.php';
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
