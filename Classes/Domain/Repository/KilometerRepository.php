<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Sebastian Schreiber
 */
class KilometerRepository
{
    public function findKilometersBySettings(array $settings): array
    {
        $kilometersFromSettings = $settings['form']['kilometers'] ?? '10:10,20:20,50:50,100:100';
        $kilometerPairs = GeneralUtility::trimExplode(',', $kilometersFromSettings, true);
        $kilometers = [];

        foreach ($kilometerPairs as $kilometerPair) {
            [$label, $value] = GeneralUtility::trimExplode(':', $kilometerPair, true, 2);
            // This is for backwards compatibility reasons, if we have something like 10,20,30 and so on
            $value = ($value !== '')?$value:$label;
            $kilometers[$value] = $label;
        }

        return $kilometers;
    }
}
