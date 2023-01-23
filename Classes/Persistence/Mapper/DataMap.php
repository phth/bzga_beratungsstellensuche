<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Persistence\Mapper;

use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;

/**
 * @author Sebastian Schreiber
 */
class DataMap
{
    /**
     * @var string[]
     */
    private $cachedTableNames = [];

    /**
     * @var DataMapFactory
     */
    private $dataMapFactory;

    public function __construct(DataMapFactory $dataMapFactory)
    {
        $this->dataMapFactory = $dataMapFactory;
    }

    public function getTableNameByClassName(string $className): string
    {
        if (!isset($this->cachedTableNames[$className])) {
            $dataMap = $this->dataMapFactory->buildDataMap($className);
            $this->cachedTableNames[$className] = $dataMap->getTableName();
        }

        return $this->cachedTableNames[$className];
    }
}
