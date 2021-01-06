<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\ViewHelpers;

use Bzga\BzgaBeratungsstellensuche\ViewHelpers\ImplodeViewHelper;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

class ImplodeViewHelperTest extends ViewHelperBaseTestcase
{

    /**
     * @var ImplodeViewHelper|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(ImplodeViewHelper::class)->setMethods(['renderChildren'])->getMock();
        $this->injectDependenciesIntoViewHelper($this->subject);
    }

    /**
     * @test
     * @dataProvider possibleValidValues
     */
    public function renderPossibleValues($input, $expected)
    {
        $this->subject->expects(self::once())->method('renderChildren')->willReturn($input);

        $this->setArgumentsUnderTest($this->subject);
        self::assertEquals($expected, $this->subject->render());
    }

    /**
     * @test
     * @dataProvider possibleInvalidValues
     */
    public function renderThrowsException($pieces)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->setArgumentsUnderTest($this->subject, ['pieces' => $pieces]);
        $this->subject->render();
    }

    /**
     * @return array
     */
    public function possibleInvalidValues(): array
    {
        $objectStorage = new ObjectStorage();
        $objectStorage->attach(new \stdClass());

        return [
            [new \stdClass()],
            [$objectStorage]
        ];
    }

    /**
     * @return array
     */
    public function possibleValidValues()
    {
        $objectStorage = new ObjectStorage();
        $class = new ObjectToString(1);
        $objectStorage->attach($class);
        $class = new ObjectToString(2);
        $objectStorage->attach($class);
        $class = new ObjectToString(3);
        $objectStorage->attach($class);

        return [
            [[1, 2, 3], '1,2,3'],
            [['Title', 'Subject', 'Text'], 'Title,Subject,Text'],
            [$objectStorage, '1,2,3']
        ];
    }
}

class ObjectToString
{

    /**
     * @var string
     */
    private $title;

    /**
     * ObjectToString constructor.
     *
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->title;
    }
}
