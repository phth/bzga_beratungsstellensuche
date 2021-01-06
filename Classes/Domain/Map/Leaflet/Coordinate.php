<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map\Leaflet;

use Bzga\BzgaBeratungsstellensuche\Domain\Map\CoordinateInterface;
use Netzmacht\LeafletPHP\Value\LatLng;

final class Coordinate implements CoordinateInterface
{
    /**
     * @var LatLng
     */
    private $coordinate;

    public function __construct(float $latitude, float $longitude)
    {
        $this->coordinate = new LatLng($latitude, $longitude);
    }

    public function getCoordinate(): LatLng
    {
        return $this->coordinate;
    }
}
