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
 * Dispatched in the show action of the entry controller before the view values are assigned. This event can modify the
 * values assigned to the view.
 * Replaces the signal `Events::SHOW_ACTION_SIGNAL`
 */
final class BeforeShowActionViewAssignedEvent
{
    private array $assignedViewValues;

    /**
     * @param array $assignedViewValues
     */
    public function __construct(array $assignedViewValues)
    {
        $this->assignedViewValues = $assignedViewValues;
    }

    /**
     * @return array
     */
    public function getAssignedViewValues(): array
    {
        return $this->assignedViewValues;
    }

    /**
     * @param array $assignedViewValues
     */
    public function setAssignedViewValues(array $assignedViewValues): void
    {
        $this->assignedViewValues = $assignedViewValues;
    }
}
