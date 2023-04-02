<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Parsers;

use App\CommissionCalculator\Exceptions\InvalidFileExtension;
use App\CommissionCalculator\Filesystem\Local as Filesystem;
use App\CommissionCalculator\Interfaces\Collectionable;
use App\CommissionCalculator\Transformers\Collection;

class Csv extends Filesystem implements Collectionable
{
    /**
     * Undocumented variable.
     *
     * @var array
     */
    protected $collections = [];

    /**
     * Undocumented function.
     *
     * @param string $path path of your csv file
     */
    public function __construct($path)
    {
        if (!$this->isCsv($path)) {
            throw new InvalidFileExtension(null, 0, '.csv');
        }

        $this->setPath($path);
    }

    /**
     * {@inheritdoc}
     */
    public function collections(): array
    {
        if (empty($this->collections)) {
            foreach ($this->getRecords() as $record) {
                $this->collections[] = new Collection($record);
            }
        }

        return $this->collections;
    }

    /**
     * Undocumented function.
     *
     * @return array
     */
    protected function getRecords()
    {
        $data = [];
        $fp = fopen($this->getPath(), 'rb');

        while (!feof($fp)) {
            if (is_array($appendable = fgetcsv($fp))) {
                $data[] = $appendable;
            }
        }

        fclose($fp);

        return $data;
    }

    /**
     * Undocumented function.
     *
     * @param string $path
     *
     * @return bool
     */
    private function isCsv($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext === 'csv') {
            return true;
        }

        return false;
    }
}
