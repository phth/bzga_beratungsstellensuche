<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map;

interface MapBuilderInterface
{
    public function build(MapInterface $map): string;

    public function createMap(string $mapId): MapInterface;

    public function createMarker(string $identifier, CoordinateInterface $coordinate): MarkerInterface;

    public function createCoordinate(float $latitude, float $longitude): CoordinateInterface;

    public function createPopUp(string $identifier): PopUpInterface;

    public function createMarkerCluster(string $identifier, MapInterface $map): MarkerClusterInterface;
}
