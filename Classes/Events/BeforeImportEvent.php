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
    private XmlImporter $xmlImporter;
    private ?\SimpleXMLIterator $sxe = null;
    private ?int $pid = null;
    private Serializer $serializer;

    /**
     * @param XmlImporter $xmlImporter
     * @param \SimpleXMLIterator|null $sxe
     * @param int|null $pid
     * @param Serializer $serializer
     */
    public function __construct(XmlImporter $xmlImporter, ?\SimpleXMLIterator $sxe, ?int $pid, Serializer $serializer)
    {
        $this->xmlImporter = $xmlImporter;
        $this->sxe = $sxe;
        $this->pid = $pid;
        $this->serializer = $serializer;
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
