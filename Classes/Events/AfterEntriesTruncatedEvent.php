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
 * Dispatched after the entries have been truncated. Replaces the signal `Events::TABLE_TRUNCATE_ALL_SIGNAL`
 */
final class AfterEntriesTruncatedEvent
{
    public function __construct(private readonly array $entries)
    {
    }

    public function getEntries(): array
    {
        return $this->entries;
    }
}
