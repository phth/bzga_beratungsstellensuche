<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Factory;

use Bzga\BzgaBeratungsstellensuche\Service\SettingsService;
use Geocoder\Provider\GoogleMaps\GoogleMaps;
use Geocoder\Provider\Nominatim\Nominatim;
use Geocoder\Provider\Provider;
use GuzzleHttp\ClientInterface;
use Http\Adapter\Guzzle7\Client;
use RuntimeException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Sebastian Schreiber
 */
class GeocoderFactory
{
    /**
     * @var string
     */
    private const TYPE_GOOGLE = 'GoogleMaps';

    /**
     * @var string
     */
    private const TYPE_OPEN_STREET_MAP = 'OpenStreetMap';
    private ClientInterface $client;
    private SettingsService $settingsService;

    public function __construct(ClientInterface $client, SettingsService $settingsService)
    {
        $this->client = $client;
        $this->settingsService = $settingsService;
    }

    public function createInstance(): Provider
    {
        $type = $this->settingsService->getByPath('geocoder') ?? GeocoderFactory::TYPE_OPEN_STREET_MAP;

        $client = new Client($this->client);

        if ($type === self::TYPE_OPEN_STREET_MAP) {
            return Nominatim::withOpenStreetMapServer($client, 'User-Agent');
        }

        if ($type === self::TYPE_GOOGLE) {
            return new GoogleMaps($client, $this->settingsService->getByPath('map.region'), $this->settingsService->getByPath('map.apiKey'));
        }

        $customProvider = GeneralUtility::makeInstance($type);

        if (! $customProvider instanceof Provider) {
            throw new RuntimeException(sprintf('The %s must implement the %s interface', $type, Provider::class));
        }

        return $customProvider;
    }
}
