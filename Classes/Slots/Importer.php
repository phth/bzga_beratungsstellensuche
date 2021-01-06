<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Slots;

use Bzga\BzgaBeratungsstellensuche\Domain\Repository\EntryRepository;
use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Serializer as BaseSerializer;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\XmlImporter;
use SimpleXMLIterator;

/**
 * @author Sebastian Schreiber
 */
class Importer
{

    /**
     * @var EntryRepository
     */
    protected $entryRepository;

    public function injectEntryRepository(EntryRepository $entryRepository): void
    {
        $this->entryRepository = $entryRepository;
    }

    public function truncateAll(XmlImporter $importer, SimpleXMLIterator $sxe, int $pid, BaseSerializer $serializer): void
    {
        $this->entryRepository->truncateAll();
    }
}
