<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

use Bzga\BzgaBeratungsstellensuche\Domain\Serializer\Serializer;
use Bzga\BzgaBeratungsstellensuche\Service\Importer\XmlImporter;

/**
 * Dispatched before the XML import is run
 * Replaces the signal `Events::PRE_IMPORT_SIGNAL`
 */
final class BeforeImportEvent
{
    public function __construct(private readonly XmlImporter $xmlImporter, private readonly ?\SimpleXMLIterator $sxe, private readonly ?int $pid, private readonly Serializer $serializer)
    {
    }

    /**
     * @return XmlImporter
     */
    public function getXmlImporter(): XmlImporter
    {
        return $this->xmlImporter;
    }

    /**
     * @return \SimpleXMLIterator|null
     */
    public function getSxe(): ?\SimpleXMLIterator
    {
        return $this->sxe;
    }

    /**
     * @return int|null
     */
    public function getPid(): ?int
    {
        return $this->pid;
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
