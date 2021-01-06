<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Model;

/**
 * @author Sebastian Schreiber
 */
trait GeopositionTrait
{
    /**
     * @var float
     */
    protected $longitude = 0.0;

    /**
     * @var float
     */
    protected $latitude = 0.0;

    public function getLongitude(): float
    {
        return (float)$this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = (float)$longitude;
    }

    public function getLatitude(): float
    {
        return (float)$this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = (float)$latitude;
    }
}
