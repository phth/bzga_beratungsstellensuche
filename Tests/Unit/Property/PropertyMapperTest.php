<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\Property;

use Bzga\BzgaBeratungsstellensuche\Property\PropertyMapper;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverterBeforeInterface;
use Bzga\BzgaBeratungsstellensuche\Property\TypeConverterInterface;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PropertyMapperTest extends UnitTestCase
{

    /**
     * @var ObjectManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $objectManager;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|PropertyMapper
     */
    protected $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(PropertyMapper::class, ['getRegisteredTypeConverters']);
    }

    /**
     * @test
     */
    public function supportsReturnsTypeConverter()
    {
        $typeConverter = $this->setUpTypeConverter();
        self::assertSame($typeConverter, $this->subject->supports($typeConverter));
    }

    /**
     * @test
     */
    public function convertSuccessfully()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|TypeConverterBeforeInterface  $typeConverter */
        $typeConverter = $this->setUpTypeConverter();
        $typeConverter->expects(self::once())->method('convert')->willReturn(true);
        self::assertTrue($this->subject->convert('array'));
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|TypeConverterBeforeInterface
     */
    private function setUpTypeConverter()
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|TypeConverterBeforeInterface  $typeConverter */
        $typeConverter  = $this->getMockBuilder(TypeConverterBeforeInterface::class)->getMock();
        $typeConverter->expects(self::once())->method('supports')->willReturn(true);
        $this->subject->expects(self::once())->method('getRegisteredTypeConverters')->willReturn([get_class($typeConverter)]);

        $this->injectObjectManager($typeConverter);
        return $typeConverter;
    }

    /**
     * @param $typeConverter TypeConverterInterface
     * @internal param array $typeConverters
     */
    private function injectObjectManager($typeConverter)
    {
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $this->objectManager->expects(self::once())->method('get')->willReturn($typeConverter);
        $this->inject($this->subject, 'objectManager', $this->objectManager);
    }
}
