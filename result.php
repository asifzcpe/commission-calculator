<?php

/**
 * We can modify this file as per our need. We can access to all the properties of the collection
 * but, in task documentation it is required to show only calculated amount of commission
 */
foreach ($collections as $collection) {
    // We can access the collection's any property here,
    echo $collection->getValue('roundUpFee') . "\n";
}

echo sprintf("Time spent in running the above script: %s seconds\n", microtime(true) - COMMISSION_CALCULATION_START);
