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
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverterInterface;
use InvalidArgumentException;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * @author Sebastian Schreiber
 */
class ObjectStorageConverter implements TypeConverterBeforeInterface
{
    /**
     * @inheritDoc
     */
    public function supports($source, string $type = TypeConverterInterface::CONVERT_BEFORE)
    {
        if (!$source instanceof ObjectStorage) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $source
     */
    public function convert($source, array $configuration = null): string
    {
        if (!$source instanceof ObjectStorage) {
            throw new InvalidArgumentException(sprintf('The %s type is not allowed', gettype($source)));
        }

        $items = array_filter($source->toArray(), static function ($item) {
            return $item instanceof DomainObjectInterface;
        });

        if (count($items) !== $source->count()) {
            throw new InvalidArgumentException('The storage contains values not of type AbstractEntity');
        }

        return implode(',', array_map(static function (DomainObjectInterface $item) {
            return $item->getUid();
        }, $items));
    }
}
