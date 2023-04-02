<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Parsers;

use App\CommissionCalculator\Interfaces\Collectionable;
use App\CommissionCalculator\Transformers\Collection;

/**
 * A parser for JSON data.
 */
class Jsonable implements Collectionable
{
    /**
     * The JSON data to be parsed.
     *
     * @var string
     */
    protected $jsonData;

    /**
     * The parsed collections.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Constructor.
     *
     * @param string $jsonData the JSON data to be parsed
     */
    public function __construct(string $jsonData)
    {
        $this->jsonData = $jsonData;
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            $data = json_decode($this->jsonData, true);

            foreach ($data as $datum) {
                $this->collections[] = new Collection($datum);
            }
        }

        return $this->collections;
    }
}
