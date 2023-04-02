<?php

declare(strict_types=1);

namespace Tests\App\CommissionCalculator\Services;

use App\CommissionCalculator\Services\CacheGlobals;
use PHPUnit\Framework\TestCase;

class CacheGlobalsTest extends TestCase
{
    public function testCacheMethods(): void
    {
        $cache = CacheGlobals::make();

        // test adding a value to the cache
        $this->assertTrue($cache->add('foo', 'bar'));

        // test retrieving a value from the cache
        $this->assertEquals('bar', $cache->get('foo'));

        // test checking if a value exists in the cache
        $this->assertTrue($cache->has('foo'));

        // test removing a value from the cache
        $this->assertTrue($cache->remove('foo'));

        // test retrieving a non-existent value from the cache
        $this->assertNull($cache->get('foo'));

        // test checking if a non-existent value exists in the cache
        $this->assertFalse($cache->has('foo'));
    }
}
