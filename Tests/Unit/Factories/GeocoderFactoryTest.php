<?php

declare(strict_types=1);

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Factories;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Bzga\BzgaBeratungsstellensuche\Factories\GeocoderFactory;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\Provider\Provider;
use Http\Client\HttpClient;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class GeocoderFactoryTest extends UnitTestCase
{

    /**
     * @var HttpClient
     */
    protected $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        parent::setUp();
    }

    /**
     * @test
     */
    public function googleMapsGeocoderReturned()
    {
        self::assertInstanceOf(
            GoogleMaps::class,
            GeocoderFactory::createInstance(GeocoderFactory::TYPE_GOOGLE, $this->httpClient)
        );
    }

    /**
     * @test
     */
    public function openStreetMapGeocoderReturned()
    {
        self::assertInstanceOf(
            Nominatim::class,
            GeocoderFactory::createInstance(GeocoderFactory::TYPE_OPEN_STREET_MAP, $this->httpClient)
        );
    }

    /**
     * @test
     */
    public function wrongTypeFallbackToGoogleMaps()
    {
        self::assertInstanceOf(
            GoogleMaps::class,
            GeocoderFactory::createInstance('something', $this->httpClient)
        );
    }

    /**
     * @test
     */
    public function customProviderReturned()
    {
        $customProvider = $this->getMockBuilder(Provider::class)->getMock();
        self::assertInstanceOf(get_class($customProvider), GeocoderFactory::createInstance(get_class($customProvider), $this->httpClient));
    }
}
