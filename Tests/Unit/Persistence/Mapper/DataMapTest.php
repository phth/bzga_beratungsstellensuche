<?php

declare(strict_types=1);

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Persistence\Mapper;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use Bzga\BzgaBeratungsstellensuche\Persistence\Mapper\DataMap;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMap as CoreDataMap;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapFactory;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @author Sebastian Schreiber
 */
class DataMapTest extends UnitTestCase
{

    /**
     * @var DataMap
     */
    protected $subject;

    /**
     * @var DataMapFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $dataMapFactory;

    protected function setUp(): void
    {
        $this->dataMapFactory = $this->getMockBuilder(DataMapFactory::class)->disableOriginalConstructor()->getMock();
        $this->subject = new DataMap($this->dataMapFactory);
    }

    /**
     * @test
     */
    public function getTableNameByClassNameCalledOnceForSameClassName()
    {
        $expectedTableName = 'tablename';
        $dataMap = $this->getMockBuilder(CoreDataMap::class)->disableOriginalConstructor()->getMock();
        $this->dataMapFactory->expects(self::once())->method('buildDataMap')->willReturn($dataMap);
        $dataMap->expects(self::once())->method('getTableName')->willReturn($expectedTableName);
        for ($i = 0; $i <= 5; $i++) {
            $tableName = $this->subject->getTableNameByClassName(__CLASS__);
        }
        self::assertSame($expectedTableName, $tableName);
    }
}
