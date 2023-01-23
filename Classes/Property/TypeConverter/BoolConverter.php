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

final class BoolConverter implements TypeConverterBeforeInterface
{
    /**
     * @inheritDoc
     */
    public function supports($source, string $type = self::CONVERT_BEFORE)
    {
        return is_bool($source);
    }

    /**
     * @param mixed $source
     */
    public function convert($source, array $configuration = null): string
    {
        return ($source === true)?'1':'0';
    }
}
