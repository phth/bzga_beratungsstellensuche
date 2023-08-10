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
use Bzga\BzgaBeratungsstellensuche\Service\SettingsService;
use Geocoder\Exception\CollectionIsEmpty;
use Geocoder\Location;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;

/**
 * @author Sebastian Schreiber
 */
class GeolocationService implements GeolocationServiceInterface
{
    /**
     * @var string
     */
    final public const DISTANCE_SQL_FIELD = '(6371.01 * acos(cos(radians(%1$f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$f)) + sin(radians(%1$f) ) * sin(radians(latitude))))';

    /**
     * @var float
     */
    final public const EARTH_RADIUS = 6371.01;

    /**
     * @var int
     */
    final public const DEFAULT_RADIUS = 10;

    public function __construct(protected SettingsService $settingsService, protected Provider $geocoder)
    {
    }

    public function calculateDistance(GeopositionInterface $demandPosition, GeopositionInterface $locationPosition): float
    {
        return self::EARTH_RADIUS * acos(
            cos(deg2rad($demandPosition->getLatitude())) * cos(deg2rad($locationPosition->getLatitude())) * cos(
                deg2rad($locationPosition->getLongitude()) - deg2rad($demandPosition->getLongitude())
            ) + sin(deg2rad($demandPosition->getLatitude())) * sin(deg2rad($locationPosition->getLatitude()))
        );
    }

    public function getDistanceSqlField(GeoPositionDemandInterface $demandPosition, string $table, string $alias = 'distance'): string
    {
        return sprintf(
            self::DISTANCE_SQL_FIELD,
            $demandPosition->getLatitude(),
            $demandPosition->getLongitude()
        ) . ' AS ' . $alias;
    }
    public function findAddressByDemand(Demand $demand): ?Location
    {
        if ($demand->getLocation()) {
            try {
                return $this->geocoder->geocodeQuery(GeocodeQuery::create($demand->getAddressToGeocode()))->first();
            } catch (CollectionIsEmpty) {
                return null;
            }
        }

        return null;
    }
}
