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
use Geocoder\Exception\CollectionIsEmpty;
use Geocoder\Location;
use Geocoder\Query\GeocodeQuery;

/**
 * @author Sebastian Schreiber
 */
class GeolocationService extends AbstractGeolocationService
{
    public function findAddressByDemand(Demand $demand): ?Location
    {
        if ($demand->getLocation()) {
            try {
                return $this->geocoder->geocodeQuery(GeocodeQuery::create($demand->getAddressToGeocode()))->first();
            } catch (CollectionIsEmpty $e) {
                return null;
            }
        }

        return null;
    }
}
