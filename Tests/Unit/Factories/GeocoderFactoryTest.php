<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Factories;

use Bzga\BzgaBeratungsstellensuche\Factories\GeocoderFactory;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\Provider\Provider;
use Http\Client\HttpClient;
use Psr\Http\Client\ClientInterface;
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

        self::assertInstanceOf(ClientInterface::class, $this->httpClient);
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
