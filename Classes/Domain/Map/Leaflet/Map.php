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
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MapInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Map\MarkerInterface;
use Netzmacht\LeafletPHP\Definition\Map as LeafletMap;
use Netzmacht\LeafletPHP\Definition\UI\Marker;

final class Map implements MapInterface
{
    /**
     * @var LeafletMap
     */
    private $map;

    public function __construct(LeafletMap $map)
    {
        $this->map = $map;
    }

    public function getMap(): LeafletMap
    {
        return $this->map;
    }

    /**
     * @param mixed $value
     */
    public function setOption(string $key, $value): void
    {
        $this->map->setOption($key, $value);
    }

    public function setCenter(CoordinateInterface $coordinate): void
    {
        $this->map->setCenter($coordinate->getCoordinate());
    }

    public function addMarker(MarkerInterface $marker): void
    {
        /** @var Marker $leafletMarker */
        $leafletMarker = $marker->getMarker();
        $leafletMarker->addTo($this->map);
        $this->map->addLayer($leafletMarker);
    }
}
