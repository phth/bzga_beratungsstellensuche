<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Service\Importer;

use Bzga\BzgaBeratungsstellensuche\Domain\Manager\CategoryManager;
use Bzga\BzgaBeratungsstellensuche\Domain\Manager\EntryManager;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Serializer;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\Exception\ContentCouldNotBeFetchedException;
use TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use UnexpectedValueException;

/**
 * @author Sebastian Schreiber
 */
abstract class AbstractImporter implements ImporterInterface
{

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var EntryManager
     */
    protected $entryManager;

    /**
     * @var CategoryManager
     */
    protected $categoryManager;

    /**
     * @var Dispatcher
     */
    protected $signalSlotDispatcher;

    public function importFromFile(string $file, int $pid = 0): void
    {
        $file = GeneralUtility::getFileAbsFileName($file);

        if (! file_exists($file)) {
            throw new FileDoesNotExistException(sprintf('The file %s does not exists', $file));
        }

        $content = file_get_contents($file);

        if (false === $content) {
            throw new ContentCouldNotBeFetchedException('The content could not be fetched');
        }

        $this->import($content, $pid);
    }

    public function importFromUrl(string $url, int $pid = 0): void
    {
        if (! GeneralUtility::isValidUrl($url)) {
            throw new UnexpectedValueException(sprintf('This is not a valid url: %s', $url));
        }

        $content = GeneralUtility::getUrl($url);
        if (false === $content) {
            throw new ContentCouldNotBeFetchedException('The content could not be fetched');
        }

        $this->import($content, $pid);
    }

    public function injectCategoryManager(CategoryManager $categoryManager): void
    {
        $this->categoryManager = $categoryManager;
    }

    public function injectEntryManager(EntryManager $entryManager): void
    {
        $this->entryManager = $entryManager;
    }

    public function injectSerializer(Serializer $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function injectSignalSlotDispatcher(Dispatcher $signalSlotDispatcher): void
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
    }
}
