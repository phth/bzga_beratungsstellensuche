<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Utility;

use TYPO3\CMS\Core\SingletonInterface;

/**
 * @author Sebastian Schreiber
 */
class FormFields implements SingletonInterface
{
    public function getAvailableFormFields(): array
    {
        $formFields = [];

        // Check if the layouts are extended by ext_tables
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['formFields'])
            && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['formFields'])
        ) {
            $formFields = $GLOBALS['TYPO3_CONF_VARS']['EXT']['bzga_beratungsstellensuche']['formFields'];
        }

        return $formFields;
    }
}
