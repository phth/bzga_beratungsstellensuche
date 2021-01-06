<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Property\TypeConverter;

use Bzga\BzgaBeratungsstellensuche\Property\TypeConverterBeforeInterface;

final class StringConverter implements TypeConverterBeforeInterface
{
    /**
     * @var string
     */
    private $allowedTags = '<p><ul><li><em><i><b><br>';

    /**
     * @inheritDoc
     */
    public function supports($source, string $type = self::CONVERT_BEFORE)
    {
        return is_string($source) && $source !== strip_tags($source, $this->allowedTags);
    }

    /**
     * @param mixed $source
     */
    public function convert($source, array $configuration = null): string
    {
        return strip_tags($source, $this->allowedTags);
    }
}
