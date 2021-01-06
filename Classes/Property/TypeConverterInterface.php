<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Property;

/**
 * @author Sebastian Schreiber
 */
interface TypeConverterInterface
{

    /**
     * @var string
     */
    public const CONVERT_BEFORE = 'before.converter';

    /**
     * @var string
     */
    public const CONVERT_AFTER = 'after.converter';

    /**
     * @param mixed $source
     * @return bool|TypeConverterInterface
     */
    public function supports($source, string $type = self::CONVERT_BEFORE);

    /**
     * @param mixed $source
     * @return mixed
     */
    public function convert($source, array $configuration = null);
}
