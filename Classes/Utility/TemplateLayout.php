<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @author Sebastian Schreiber
 */
class TemplateLayout implements SingletonInterface
{
    public function getAvailableTemplateLayouts(?int $pageUid): array
    {
        $templateLayouts = [];

        // Check if the layouts are extended by ext_tables
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['templateLayouts'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['templateLayouts'])
        ) {
            $templateLayouts = $GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['templateLayouts'];
        }

        // Add TsConfig values
        foreach ($this->getTemplateLayoutsFromTsConfig($pageUid) as $templateKey => $title) {
            if (\str_starts_with((string)$title, '--div--')) {
                [$templateKey, $title] = GeneralUtility::trimExplode(',', $title, true, 2);
            }
            $templateLayouts[] = [$title, $templateKey];
        }

        return $templateLayouts;
    }

    private function getTemplateLayoutsFromTsConfig(?int $pageUid): array
    {
        $templateLayouts = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig['tx_bzgaberatungsstellensuche.']['templateLayouts.']) && is_array($pagesTsConfig['tx_bzgaberatungsstellensuche.']['templateLayouts.'])) {
            $templateLayouts = $pagesTsConfig['tx_bzgaberatungsstellensuche.']['templateLayouts.'];
        }

        return $templateLayouts;
    }
}
