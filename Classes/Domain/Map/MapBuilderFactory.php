<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Map;

use Bzga\BzgaBeratungsstellensuche\Domain\Map\Leaflet\MapBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class MapBuilderFactory
{
    public function createMapBuilder(): MapBuilderInterface
    {
        return GeneralUtility::makeInstance(MapBuilder::class);
    }
}
