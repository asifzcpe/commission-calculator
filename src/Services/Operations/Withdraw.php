<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Services\Operations;

use App\CommissionCalculator\Services\CacheGlobals as Cache;
use App\CommissionCalculator\Services\Math;
use App\CommissionCalculator\Traits\ExchangeSetterTrait;
use App\CommissionCalculator\Transformers\Collection;

class Withdraw
{
    use ExchangeSetterTrait;

    public const COMMISSION_FEE = 0.3;
    public const BUSINESS_MINIMUM = 0.5;
    public const PRIVATE_FREE_PER_WEEK = 1000;
    public const PRIVATE_FREE_MAX_WITHDRAW = 3;

    /**
     * Undocumented variable.
     *
     * @var \App\CommissionCalculator\Transformers\Collection
     */
    private $collection;

    private $cache;

    /**
     * Undocumented function.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->cache = Cache::make();
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function fee()
    {
        $type = $this->collection->userType();

        return Math::add(0, $this->{'feeFor'.ucfirst($type)}());
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForPrivate()
    {
        $amount = $this->calculatePrivateAmount();

        return Math::mul(
            $amount,
            Math::div(static::COMMISSION_FEE, 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    protected function feeForBusiness()
    {
        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        if ($operationAmount <= static::BUSINESS_MINIMUM) {
            return 0;
        }

        return Math::mul(
            $this->collection->amount(),
            Math::div(static::COMMISSION_FEE, 100),
        );
    }

    /**
     * Undocumented function.
     *
     * @return float
     */
    protected function calculatePrivateAmount()
    {
        $this->incrementWithdrawAttempt();

        $allocated = $this->getUserAllocatedFreeWeek();

        $operationAmount = $this->exchange->convert(
            $this->collection->currency(),
            $this->collection->amount()
        );

        // we need to know the allocated + the collection's amount
        // we will call it as our base value for now...
        $basis = Math::add($allocated, $operationAmount);

        // this condition, where we return 0, meaning that
        // the purchases still under the free week quota
        // thus, we shall return 0 instead.
        if (
            $this->isWithdrawFreeWeek($basis)
            && $this->stillInMinimumWithdraw()
        ) {
            $this->updateUserAllocatedFreeWeek(
                Math::add($allocated, $operationAmount)
            );

            return 0.00;
        }

        // this is where we determine if our basis is greater than
        // the quota, thus, we need to pre-calculate the value that
        // we need to deduct from the remaining quota it has
        $remaining = Math::sub(
            static::PRIVATE_FREE_PER_WEEK,
            $allocated
        );

        $this->updateUserAllocatedFreeWeek(
            Math::add($allocated, $remaining)
        );

        $amount = Math::sub((float) $operationAmount, $remaining);

        return $this->exchange->convertBack(
            $this->collection->currency(),
            $amount
        );
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getUserAllocatedFreeWeek()
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        if (!$this->cache->has($key)) {
            $this->cache->add($key, 0);
        }

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @param Collection $collection
     * @param mixed      $value
     *
     * @return bool
     */
    protected function updateUserAllocatedFreeWeek($value)
    {
        $key = sprintf('%s-allocated', $this->generateTagKey());

        $this->cache->add($key, $value);

        return true;
    }

    /**
     * Undocumented function.
     *
     * @return string|float
     */
    protected function getWithdrawAttempts()
    {
        $key = sprintf('%s-withdraw-attempts', $this->generateTagKey());

        return $this->cache->get($key);
    }

    /**
     * Undocumented function.
     *
     * @return bool
     */
    protected function incrementWithdrawAttempt()
    {
        $key = sprintf(
            '%s-withdraw-attempts',
            $this->generateTagKey($this->collection)
        );

        if ($this->cache->has($key)) {
            $this->cache->add(
                $key,
                Math::add($this->cache->get($key), 1, 0)
            );
        } else {
            $this->cache->add($key, 1);
        }

        return true;
    }

    /**
     *  This is a protected function that generates a unique key for a tag based on the current year, week, and user ID.

     *  The function first obtains the year and week using the getYearAndWeek() method, and checks if the week is the first week of December.

     *  If so, it increments the year to account for the fact that the first week of December is also considered the last week of the year.

     *  Next, the function sets the interpreted_year and interpreted_week values in the collection object using the obtained year and week.

     *  Finally, the function generates a unique key string by replacing placeholders for year, week, and user ID with their respective values.

     *  The generated key is returned.

     *
     *  @return string a unique key string for the tag
     */
    protected function generateTagKey()
    {
        list($year, $month, $week) = $this->getYearAndWeek();

        if ($month === 12 && $week === 1) {
            ++$year;
        }

        $this->collection->setValue('interpreted_year', $year);
        $this->collection->setValue('interpreted_week', $week);

        return strtr('{year}-{week}-{user}', [
            '{year}' => $year,
            '{week}' => $week,
            '{user}' => $this->collection->userId(),
        ]);
    }

    protected function getYearAndWeek()
    {
        $date = new \DateTime($this->collection->date());

        return [
            (int) $date->format('Y'),
            (int) $date->format('m'),
            (int) $date->format('W'),
        ];
    }

    protected function stillInMinimumWithdraw()
    {
        if ($this->getWithdrawAttempts() <= static::PRIVATE_FREE_MAX_WITHDRAW) {
            return true;
        }

        return false;
    }

    /**
     * Check if the withdrawal is free for the current week based on the given basis.
     *
     * @param float $basis the withdrawal basis
     *
     * @return bool true if the withdrawal is free for the current week, false otherwise
     */
    protected function isWithdrawFreeWeek($basis)
    {
        // This code uses a range to compare two numbers with decimal places,
        // instead of the literal equal operator.
        // For example, instead of writing "300 == 300.0000000",
        // we can use a range to account for the decimal places and compare them as equal.
        if (
            $basis >= static::PRIVATE_FREE_PER_WEEK &&
            $basis <= static::PRIVATE_FREE_PER_WEEK
        ) {
            return true;
        }

        if ($basis < static::PRIVATE_FREE_PER_WEEK) {
            return true;
        }

        return false;
    }
}
