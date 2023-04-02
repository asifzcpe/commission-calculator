<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Exceptions;

/**
 * Exception thrown when an unexpected file extension is encountered.
 */
class InvalidFileExtension extends \Exception
{
    /**
     * Construct a new instance of the exception.
     *
     * @param string|null $expectedExtension the expected file extension, if available
     * @param int         $code              the error code, if available
     */
    public function __construct(?string $expectedExtension = null, int $code = 0)
    {
        if ($expectedExtension === null) {
            parent::__construct('Wrong file extension.', $code);
        } else {
            parent::__construct("Expected extension must be [{$expectedExtension}] format", $code);
        }
    }
}
