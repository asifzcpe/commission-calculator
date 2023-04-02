<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Services;

use App\CommissionCalculator\Interfaces\Cacheable;
use App\CommissionCalculator\Traits\MakeableTrait;

class CacheGlobals implements Cacheable
{
    use MakeableTrait;

    /**
     * Removes a value from the cache.
     *
     * @param string $key the key of the value to remove
     *
     * @return bool true if the value was removed, false otherwise
     */
    public function remove(string $key): bool
    {
        unset($GLOBALS[$key]);

        return true;
    }

    /**
     * Adds a value to the cache.
     *
     * @param string $key   the key under which to store the value
     * @param mixed  $value the value to store
     *
     * @return bool true on success, false otherwise
     */
    public function add(string $key, $value): bool
    {
        $GLOBALS[$key] = $value;

        return true;
    }

    /**
     * Checks if a value is stored in the cache.
     *
     * @param string $key the key to check
     *
     * @return bool true if the value exists, false otherwise
     */
    public function has(string $key): bool
    {
        return isset($GLOBALS[$key]);
    }

    /**
     * Retrieves a value from the cache.
     *
     * @param string $key the key of the value to retrieve
     *
     * @return mixed the value, or null if it does not exist
     */
    public function get(string $key)
    {
        return $GLOBALS[$key] ?? null;
    }
}
