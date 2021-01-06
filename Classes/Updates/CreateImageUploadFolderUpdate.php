<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Updates;

use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

final class CreateImageUploadFolderUpdate implements UpgradeWizardInterface
{
    public function getIdentifier(): string
    {
        return self::class;
    }

    public function getTitle(): string
    {
        return '[Beratungsstellensuche] Create image folder';
    }

    public function getDescription(): string
    {
        return '';
    }

    public function executeUpdate(): bool
    {
        return GeneralUtility::mkdir($this->getDefaultImageFolder());
    }

    public function updateNecessary(): bool
    {
        return false === is_dir($this->getDefaultImageFolder());
    }

    public function getPrerequisites(): array
    {
        return [];
    }

    private function getDefaultImageFolder(): string
    {
        $storageRepository = GeneralUtility::makeInstance(ResourceFactory::class)->getDefaultStorage();
        $storageFolder = $storageRepository->getDefaultFolder()->getPublicUrl();
        return GeneralUtility::getFileAbsFileName(sprintf('%s/tx_bzgaberatungsstellensuche', rtrim($storageFolder, '/')));
    }
}
