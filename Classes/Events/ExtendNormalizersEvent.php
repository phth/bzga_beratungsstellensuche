<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Events;

/**
 *  Extends the normalizers used in the serializer. Replaces signal `Events::ADDITIONAL_NORMALIZERS_SIGNAL`
 */
final class ExtendNormalizersEvent
{
    private array $normalizers;
    private array $additionalNormalizers;

    /**
     * @param array $normalizers
     * @param array $additionalNormalizers
     */
    public function __construct(array $normalizers, array $additionalNormalizers)
    {
        $this->normalizers = $normalizers;
        $this->additionalNormalizers = $additionalNormalizers;
    }

    /**
     * @return array
     */
    public function getNormalizers(): array
    {
        return $this->normalizers;
    }

    /**
     * @return array
     */
    public function getAdditionalNormalizers(): array
    {
        return $this->additionalNormalizers;
    }

    /**
     * @param array $additionalNormalizers
     */
    public function setAdditionalNormalizers(array $additionalNormalizers): void
    {
        $this->additionalNormalizers = $additionalNormalizers;
    }
}
