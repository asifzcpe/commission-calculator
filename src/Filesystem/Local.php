<?php

declare(strict_types=1);

namespace App\CommissionCalculator\Filesystem;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class Local
{
    /**
     * Undocumented variable.
     *
     * @var string
     */
    protected $path;

    /**
     * Undocumented function.
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(null, 0, null, $path);
        }

        $this->path = $path;

        return $this;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Undocumented function.
     *
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->path);
    }
}
