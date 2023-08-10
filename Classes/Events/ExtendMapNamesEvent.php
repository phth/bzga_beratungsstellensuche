<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

/**
 *  Extends the map names used in the BaseMappingNameConverter. Replaces signal `Events::SIGNAL_MAP_NAMES`
 */
final class ExtendMapNamesEvent
{
    public function __construct(private readonly array $mapNames, private array $extendedMapNames)
    {
    }

    /**
     * @return array
     */
    public function getMapNames(): array
    {
        return $this->mapNames;
    }

    /**
     * @return array
     */
    public function getExtendedMapNames(): array
    {
        return $this->extendedMapNames;
    }

    public function setExtendedMapNames(array $extendedMapNames): void
    {
        $this->extendedMapNames = $extendedMapNames;
    }
}
