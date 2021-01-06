<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Factories;

use Http\Adapter\Guzzle6\Client;
use Http\Client\HttpClient;

/**
 * @author Sebastian Schreiber
 */
class HttpClientFactory
{
    public static function createInstance(): HttpClient
    {
        $httpOptions = $GLOBALS['TYPO3_CONF_VARS']['HTTP'];
        $httpOptions['verify'] = filter_var($httpOptions['verify'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $httpOptions['verify'];

        return Client::createWithConfig($httpOptions);
    }
}
