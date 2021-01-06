<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map;

use Netzmacht\LeafletPHP\Definition\Map as LeafletMap;

interface MapInterface
{
    public function getMap(): LeafletMap;

    /**
     * @param mixed $value
     */
    public function setOption(string $key, $value);

    public function setCenter(CoordinateInterface $coordinate);

    public function addMarker(MarkerInterface $marker);
}
