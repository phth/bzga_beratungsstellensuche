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
 * Dispatched after an entry has been deleted. Replaces the signal `Events::REMOVE_ENTRY_FROM_DATABASE_SIGNAL`
 */
final class AfterEntryDeletedEvent
{
    private int $deletedEntry;

    public function __construct(int $deletedEntry)
    {
        $this->deletedEntry = $deletedEntry;
    }

    public function getDeletedEntry(): int
    {
        return $this->deletedEntry;
    }
}
