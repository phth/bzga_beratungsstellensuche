<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Utility;

use TYPO3\CMS\Core\Core\Environment;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @author Sebastian Schreiber
 */
class Utility
{
    public static function transformQueryResultToObjectStorage(QueryResultInterface $queryResult): ObjectStorage
    {
        $objectStorage = new ObjectStorage();
        foreach ($queryResult as $item) {
            $objectStorage->attach($item);
        }

        return $objectStorage;
    }

    public static function stripPathSite(string $string): string
    {
        return substr($string, strlen(Environment::getPublicPath()));
    }
}
