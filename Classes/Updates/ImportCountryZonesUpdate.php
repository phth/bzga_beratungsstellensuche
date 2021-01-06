<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Updates;

use SJBR\StaticInfoTables\Utility\DatabaseUpdateUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

final class ImportCountryZonesUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return self::class;
    }

    public function getTitle(): string
    {
        return '[Beratungsstellensuche] Import country zones';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function executeUpdate(): bool
    {
        $databaseUpdateUtility = GeneralUtility::makeInstance(DatabaseUpdateUtility::class);
        $databaseUpdateUtility->doUpdate('bzga_beratungsstellensuche');

        return true;
    }

    public function updateNecessary(): bool
    {
        return true;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }
}
