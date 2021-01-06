<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\View\Entry;

use Bzga\BzgaBeratungsstellensuche\Domain\Model\Entry;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Mvc\View\AbstractView;

final class AutocompleteJson extends AbstractView
{
    public function render(): string
    {
        /** @var Entry[] $entries */
        $entries = $this->variables['entries'];
        $q = $this->variables['q'];

        $suggestions = [];

        foreach ($entries as $entry) {
            if (StringUtility::beginsWith($entry->getCity(), $q)) {
                $suggestions[] = $entry->getCity();
            }

            if (StringUtility::beginsWith($entry->getZip(), $q)) {
                $suggestions[] = $entry->getZip();
            }
        }

        return json_encode(array_unique($suggestions));
    }
}
