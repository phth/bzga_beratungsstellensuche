<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

use TYPO3\CMS\Extbase\Property\PropertyMappingConfiguration;

/**
 * Dispatched after the EntryController has been initialized. Replaces the signal `Events::INITIALIZE_ACTION_SIGNAL`
 */
final class AfterEntryControllerInitializedEvent
{
    public function __construct(private readonly PropertyMappingConfiguration $propertyMappingConfiguration)
    {
    }

    /**
     * @return PropertyMappingConfiguration
     */
    public function getPropertyMappingConfiguration(): PropertyMappingConfiguration
    {
        return $this->propertyMappingConfiguration;
    }
}
