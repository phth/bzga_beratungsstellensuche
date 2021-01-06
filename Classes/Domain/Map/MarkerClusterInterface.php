<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map;

use Netzmacht\LeafletPHP\Plugins\MarkerCluster\MarkerClusterGroup;

interface MarkerClusterInterface
{
    public function getMarkerCluster(): MarkerClusterGroup;

    public function setOptions(array $options);

    public function addMarker(MarkerInterface $marker): void;
}
