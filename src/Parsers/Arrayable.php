<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Parsers;

use App\CommissionCalculator\Interfaces\Collectionable;
use App\CommissionCalculator\Transformers\Collection;

class Arrayable implements Collectionable
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Undocumented function.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            foreach ($this->data as $datum) {
                $this->collections[] = new Collection($datum);
            }
        }

        return $this->collections;
    }
}
