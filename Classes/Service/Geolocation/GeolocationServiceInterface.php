<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Service\Geolocation;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Dto\Demand;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeoPositionDemandInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeopositionInterface;

/**
 * @author Sebastian Schreiber
 */
interface GeolocationServiceInterface
{
    /**
     * @return mixed
     */
    public function findAddressByDemand(Demand $demand);

    /**
     * @return mixed
     */
    public function getDistanceSqlField(GeoPositionDemandInterface $demandPosition, string $table, string $alias = 'distance');

    /**
     * @return mixed
     */
    public function calculateDistance(GeopositionInterface $demandPosition, GeopositionInterface $locationPosition);
}
