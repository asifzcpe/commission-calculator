<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Interfaces;

interface Cacheable
{
    /**
     * Removes a value from the cache.
     *
     * @param string $key the key of the value to remove
     *
     * @return bool true if the value was removed, false otherwise
     */
    public function remove(string $key): bool;

    /**
     * Adds a value to the cache.
     *
     * @param string $key   the key under which to store the value
     * @param mixed  $value the value to store
     *
     * @return bool true on success, false otherwise
     */
    public function add(string $key, $value): bool;

    /**
     * Checks if a value is stored in the cache.
     *
     * @param string $key the key to check
     *
     * @return bool true if the value exists, false otherwise
     */
    public function has(string $key): bool;

    /**
     * Retrieves a value from the cache.
     *
     * @param string $key the key of the value to retrieve
     *
     * @return mixed the value, or null if it does not exist
     */
    public function get(string $key);
}
