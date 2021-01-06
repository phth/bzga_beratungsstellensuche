<?php

declare(strict_types=1);

/*
 * This file is part of the "bzga_beratungsstellensuche" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Bzga\BzgaBeratungsstellensuche\Tests\Unit\ViewHelpers\Math;

use Bzga\BzgaBeratungsstellensuche\ViewHelpers\Math\RoundViewHelper;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;

class RoundViewHelperTest extends ViewHelperBaseTestcase
{

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|RoundViewHelper
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(RoundViewHelper::class)->setMethods(['renderChildren'])->getMock();
        $this->injectDependenciesIntoViewHelper($this->subject);
    }

    /**
     * @test
     * @dataProvider validInputValues
     */
    public function renderWithRenderChildrenValue($input, $expected, $precision)
    {
        $this->subject->expects(self::once())->method('renderChildren')->willReturn($input);
        $this->subject->setArguments(['number' => null, 'precision' => $precision]);
        self::assertEquals($expected, $this->subject->render());
    }

    /**
     * @return array
     */
    public function validInputValues()
    {
        return [
            [3.4, 3.4, 2],
            [3.6, 4, 0],
            [1.95583, 1.956, 3],
        ];
    }
}
