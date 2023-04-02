<?php

declare(strict_types=1);

namespace Tests\App\CommissionCalculator\Exceptions;

use App\CommissionCalculator\Exceptions\InvalidFileExtension;
use PHPUnit\Framework\TestCase;

class InvalidFileExtensionTest extends TestCase
{
    public function testConstructorWithExpectedExtension(): void
    {
        $expectedExtension = 'csv';
        $exception = new InvalidFileExtension($expectedExtension);

        $this->assertEquals(
            "Expected extension must be [{$expectedExtension}] format",
            $exception->getMessage()
        );
    }

    public function testConstructorWithoutExpectedExtension(): void
    {
        $exception = new InvalidFileExtension();

        $this->assertEquals(
            'Wrong file extension.',
            $exception->getMessage()
        );
    }
}
