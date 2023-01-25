<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Model\Dto;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Category;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeoPositionDemandInterface;
use Bzga\BzgaBeratungsstellensuche\Domain\Model\GeopositionTrait;

use Bzga\BzgaBeratungsstellensuche\Service\Geolocation\Decorator\GeolocationServiceCacheDecorator;
use Geocoder\Location as GeocoderAddress;
use SJBR\StaticInfoTables\Domain\Model\CountryZone;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @author Sebastian Schreiber
 */
class Demand extends AbstractValueObject implements GeoPositionDemandInterface
{
    use GeopositionTrait;

    /**
     * @var string
     */
    protected $keywords = '';

    /**
     * @var string
     */
    protected $searchFields = 'title,subtitle,description';

    /**
     * @var string
     */
    protected $location = '';

    /**
     * @var ObjectStorage<Category>
     */
    protected $categories;

    /**
     * @var int
     */
    protected $kilometers = 10;

    /**
     * @var CountryZone
     */
    protected $countryZone;

    /**
     * @var GeolocationServiceCacheDecorator
     */
    protected $geolocationService;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
        $this->geolocationService = GeneralUtility::makeInstance(GeolocationServiceCacheDecorator::class);
    }

    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }

    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function getAddressToGeocode(): string
    {
        return sprintf('Deutschland, %s', $this->location);
    }

    public function getKilometers(): int
    {
        return $this->kilometers;
    }

    public function setKilometers(int $kilometers): void
    {
        $this->kilometers = $kilometers;
    }

    public function getKeywords(): string
    {
        return $this->keywords;
    }

    public function setKeywords(string $keywords): void
    {
        $this->keywords = $keywords;
    }

    public function getSearchFields(): string
    {
        return $this->searchFields;
    }

    public function setSearchFields(string $searchFields): void
    {
        $this->searchFields = $searchFields;
    }

    public function getCountryZone(): ?CountryZone
    {
        return $this->countryZone;
    }

    public function setCountryZone(?CountryZone $countryZone): void
    {
        $this->countryZone = $countryZone;
    }

    public function getLongitude(): float
    {
        if (empty($this->longitude)) {
            $this->updateLatitudeLongitude();
        }

        return $this->longitude;
    }

    public function getLatitude(): float
    {
        if (empty($this->latitude)) {
            $this->updateLatitudeLongitude();
        }

        return $this->latitude;
    }

    private function updateLatitudeLongitude(): void
    {
        $address = $this->geolocationService->findAddressByDemand($this);
        if ($address instanceof GeocoderAddress) {
            $this->latitude = $address->getCoordinates()->getLatitude();
            $this->longitude = $address->getCoordinates()->getLongitude();
        }
    }

    public function hasValidCoordinates(): bool
    {
        $this->updateLatitudeLongitude();
        return $this->latitude !== null && $this->longitude !== null;
    }
}
